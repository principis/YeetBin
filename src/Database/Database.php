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

use App\Util\Random;
use PDO;

class Database
{
    const TEXT_PASTE = 0;

    private PDO $conn;

    public function __construct(string $dsn, ?string $username, ?string $password, ?array $options)
    {
        $this->conn = new PDO($dsn, $username, $password, $options);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query(string $query, ?array $params = null)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTextPaste(?string $title, string $language, string $content) :bool|string
    {
        $id = $this->findAvailableId();

        $stmt = $this->conn->prepare(
            'INSERT INTO pastes (`id`, `title`, `type`, `language`, `content`) VALUES (?, ?, ?, ?, ?)'
        );
        $result = $stmt->execute(array($id, $title, self::TEXT_PASTE, $language, $content));

        if ($result) {
            return $id;
        }

        return false;
    }

    public function findAvailableId() :string
    {
        do {
            $id = Random::pasteId();

            $stmt = $this->conn->prepare('SELECT `id` FROM pastes WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);

            $isAvailable = $stmt->rowCount() === 0;
        } while (!$isAvailable);

        return $id;
    }

    public function getPaste(string $id) :array|bool
    {
        $stmt = $this->conn->prepare('SELECT * FROM pastes WHERE `id` = ?;');
        $stmt->execute(array($id));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}