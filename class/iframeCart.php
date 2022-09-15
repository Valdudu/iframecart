<?php
class FrameCart{
    public static function getAllCarts(): array{
        return Db::getInstance()->executeS('select * from '._DB_PREFIX_.'iframecart');
    }
}