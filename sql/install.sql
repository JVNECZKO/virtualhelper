CREATE TABLE IF NOT EXISTS `ps_product_caretaker_assignment` (
    `id_product` INT UNSIGNED NOT NULL,
    `id_caretaker` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id_product`),
    KEY `id_caretaker` (`id_caretaker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
