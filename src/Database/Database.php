<?php

/*
 * This file is part of YeetBin.
 * Copyright (C) 2023 Arthur Bols
 *
 * YeetBin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * YeetBin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with YeetBin.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Database;

use App\Entity\Paste;
use App\Entity\TextPaste;
use App\Util\Random;
use PDO;
use PDOException;

class Database
{
    const PASTE_ID_LENGTH = 8;

    private PDO $conn;

    public function __construct(string $dsn, ?string $username, ?string $password, ?array $options)
    {
        $this->conn = new PDO($dsn, $username, $password, $options);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param Paste $paste
     * @return string
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function addPaste(Paste $paste) :string
    {
        $uid = $this->findAvailableUID();
        $type = PasteType::fromPaste($paste);

        $stmt = $this->conn->prepare(
            'INSERT INTO pastes (uid, type, title) VALUES (?, ?, ?)'
        );
        $stmt->execute([$uid, $type->value, $paste->getTitle()]);

        match ($type) {
            PasteType::TEXT => $this->addTextPaste($this->conn->lastInsertId(), $paste)
        };

        return $uid;
    }

    /**
     * @return string
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function findAvailableUID() :string
    {
        do {
            $id = Random::pasteId(self::PASTE_ID_LENGTH);

            $stmt = $this->conn->prepare('SELECT uid FROM pastes WHERE uid = ? LIMIT 1');
            $stmt->execute([$id]);

            $isAvailable = empty($stmt->fetch());
        } while (!$isAvailable);

        return $id;
    }

    /**
     * Inserts the given TextPaste. Must be called immediately after inserting the abstract Paste.
     * @param int $pasteID the ID of the abstract Paste.
     * @param TextPaste $paste
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    private function addTextPaste(int $pasteID, TextPaste $paste) :void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO text_pastes (paste_id, lang, content) VALUES (?, ?, ?)'
        );
        $stmt->execute([$pasteID, $paste->getLanguage(), $paste->getContent()]);
    }

    /**
     * @param string $uid
     * @return Paste|bool The fetched Paste or <b>FALSE</b> if no Paste with the given $uid was found.
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function getPaste(string $uid) :Paste|bool
    {
        $stmt = $this->conn->prepare('SELECT type FROM pastes WHERE uid = ?;');
        $stmt->execute([$uid]);

        $pasteData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($pasteData)) {
            return false;
        }
        $type = PasteType::from($pasteData['type']);

        return match ($type) {
            PasteType::TEXT => $this->getTextPaste($uid)
        };
    }

    /**
     * @param string $uid
     * @return TextPaste|bool The fetched TextPaste or <b>FALSE</b> if no TextPaste with the given $uid was found.
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function getTextPaste(string $uid) :TextPaste|bool
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM text_pastes AS t INNER JOIN pastes AS p on t.paste_id = p.id WHERE p.uid = ?'
        );
        $stmt->execute([$uid]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($data)) {
            return false;
        }

        return new TextPaste(
            $data['id'],
            $data['title'],
            $data['lang'],
            $data['content']
        );
    }
}