SELECT UPPER(u.`last_name`) AS 'NAME', u.`first_name`, s.`price` 
FROM ((`member` m INNER JOIN `user_card`  ON u.`id_user` = m.`id_user_card`) 
INNER JOIN `subscription` s ON s.`id_sub` = m.`id_sub`)
WHERE s.`price` > 42
ORDER BY u.`last_name`, u.`first_name`;