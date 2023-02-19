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

use App\Entity\FilePaste;
use App\Entity\ImagePaste;
use App\Entity\Paste;
use App\Entity\TextPaste;
use App\Util\ImageFormat;
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
            PasteType::TEXT => $this->addTextPaste($this->conn->lastInsertId(), $paste),
            PasteType::FILE => $this->addFilePaste($this->conn->lastInsertId(), $paste),
            PasteType::IMAGE => $this->addImagePaste($this->conn->lastInsertId(), $paste),
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
     * Inserts the given FilePaste. Must be called immediately after inserting the abstract Paste.
     * @param int $pasteID the ID of the abstract Paste.
     * @param FilePaste $paste
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    private function addFilePaste(int $pasteID, FilePaste $paste) :void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO file_pastes (paste_id, file_name, file_ext) VALUES (?, ?, ?)'
        );
        $stmt->execute([$pasteID, $paste->getOriginalName(), $paste->getOriginalExtension()]);
    }

    /**
     * Inserts the given ImagePaste. Must be called immediately after inserting the abstract Paste.
     * @param int $pasteID the ID of the abstract Paste.
     * @param ImagePaste $paste
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    private function addImagePaste(int $pasteID, ImagePaste $paste) :void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO image_pastes (paste_id, format) VALUES (?, ?)'
        );
        $stmt->execute([$pasteID, $paste->getFormat()->value]);
    }

    /**
     * Tries to remove the paste with the given uid.
     * @param string $uid
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function removePaste(string $uid) :void
    {
        $stmt = $this->conn->prepare(
            'DELETE FROM pastes WHERE uid = ?'
        );
        $stmt->execute([$uid]);
    }

    /**
     * @param string $uid
     * @return Paste|false The fetched Paste or <b>FALSE</b> if no Paste with the given $uid was found.
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function getPaste(string $uid) :Paste|false
    {
        $stmt = $this->conn->prepare('SELECT type FROM pastes WHERE uid = ?;');
        $stmt->execute([$uid]);

        $pasteData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($pasteData)) {
            return false;
        }
        $type = PasteType::from($pasteData['type']);

        return match ($type) {
            PasteType::TEXT => $this->getTextPaste($uid),
            PasteType::FILE => $this->getFilePaste($uid),
            PasteType::IMAGE => $this->getImagePaste($uid),
        };
    }

    /**
     * @param string $uid
     * @return TextPaste|false The fetched TextPaste or <b>FALSE</b> if no TextPaste with the given $uid was found.
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function getTextPaste(string $uid) :TextPaste|false
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
            $data['uid'],
            $data['title'],
            $data['lang'],
            $data['content']
        );
    }

    /**
     * @param string $uid
     * @return TextPaste|false The fetched FilePaste or <b>FALSE</b> if no FilePaste with the given $uid was found.
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function getFilePaste(string $uid) :FilePaste|false
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM file_pastes AS t INNER JOIN pastes AS p on t.paste_id = p.id WHERE p.uid = ?'
        );
        $stmt->execute([$uid]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($data)) {
            return false;
        }

        return new FilePaste(
            $data['uid'],
            $data['title'],
            $data['file_name'],
            $data['file_ext']
        );
    }

    /**
     * @param string $uid
     * @return TextPaste|false The fetched ImagePaste or <b>FALSE</b> if no ImagePaste with the given $uid was found.
     * @throws PDOException On error if PDO::ERRMODE_EXCEPTION option is true.
     */
    public function getImagePaste(string $uid) :ImagePaste|false
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM image_pastes AS t INNER JOIN pastes AS p on t.paste_id = p.id WHERE p.uid = ?'
        );
        $stmt->execute([$uid]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($data)) {
            return false;
        }

        return new ImagePaste(
            $data['uid'],
            $data['title'],
            ImageFormat::from($data['format']),
        );
    }
}