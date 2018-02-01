<?php

use Lijinma\Color;

function verifyDate($date, $strict = true)
{
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
    if ($strict) {
        $errors = DateTime::getLastErrors();
        if (!empty($errors['warning_count'])) {
            return false;
        }
    }

    return $dateTime !== false;
}

function cli_error($message) {
    cli\line(Color::BG_RED . Color::WHITE . $message . Color::BG_BLACK);
}

function cli_success($message) {
    cli\line(Color::GREEN . $message . Color::WHITE);
}

function cli_color($message, $color) {
    return $color . $message . Color::WHITE;
}

function insert_sql($table, $data, $ext = '')
{
    $intoSql = null;
    $dataSql = null;
    foreach ($data as $key => $value) {
        $intoSql .= ", $key";
        $dataSql .= ", '$value'";
    }
    $intoSql = ltrim($intoSql, ', ');
    $dataSql = ltrim($dataSql, ', ');
    $sql = 'REPLACE INTO '.$table.'('.$intoSql.') VALUES ('.$dataSql.') '.$ext;

    return $sql;
}

function getNewID($db_to, $id, $get_name = false)
{
    $id_new = $db_to->prepare('SELECT * FROM temp_info_merge WHERE rid_old = ?');
    $id_new->execute([$id]);
    $id_new = $id_new->fetch(PDO::FETCH_ASSOC);

    if ($get_name == true) {
        return $id_new['rname'];
    }

    return (int) $id_new['rid_new'];
}

function getFromName($db_to, $name, $get_name = false)
{
    $id_new = $db_to->prepare('SELECT * FROM temp_info_merge WHERE rname LIKE ?');
    $id_new->execute(['%'.$name.'%']);
    $id_new = $id_new->fetch(PDO::FETCH_ASSOC);

    if ($get_name == true) {
        return $id_new['rname'];
    }

    return (int) $id_new['rid_new'];
}

function getNewBHID($db_to, $id, $get_name = false)
{
    $id_new = $db_to->prepare('SELECT * FROM temp_banghui_merge WHERE id_old = ?');
    $id_new->execute([$id]);
    $id_new = $id_new->fetch(PDO::FETCH_ASSOC);

    if ($get_name == true) {
        return $id_new['bhname'];
    }

    return (int) $id_new['id_new'];
}
