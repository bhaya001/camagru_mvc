SELECT `name` FROM distrib WHERE
((`id_distrib`= 42) OR (`id_distrib` = 71)
OR (`id_distrib` >= 62 AND `id_distrib` <= 69)
OR (`id_distrib` >= 88 AND `id_distrib` <= 90))
OR (LENGTH(`name`)-LENGTH(REPLACE(LOWER(`name`),'y',''))) = 2
LIMIT 2,5;