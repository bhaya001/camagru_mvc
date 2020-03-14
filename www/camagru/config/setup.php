<?php
try
{
    $pdo = new PDO(DSNC, DB_USER, DB_PASS);
    $pdo->query('CREATE DATABASE IF NOT EXISTS '.DB_NAME);
    $pdo->query('USE '.DB_NAME);

    $pdo->query('CREATE TABLE `users` (
                              `id_user` int(11) NOT NULL,
                              `name` varchar(40) NOT NULL,
                              `email` varchar(255) NOT NULL UNIQUE,
                              `login` varchar(25) NOT NULL,
                              `password` varchar(255) NOT NULL,
                              `status` int(1) NOT NULL DEFAULT 0,
                              `token` varchar(255) NOT NULL,
                              `profile` varchar(255),
                              `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                              `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    $pdo->query('ALTER TABLE `users` ADD PRIMARY KEY (`id_user`);');
    $pdo->query('ALTER TABLE `users` MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;');

    $pdo->query('CREATE TABLE `images` (
                                `id_image` int(11) NOT NULL,
                                `path` varchar(255) NOT NULL UNIQUE,
                                `publisher_id` int(11) NOT NULL,
                                `is_profile` int(1) DEFAULT 0,
                                `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                                `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    $pdo->query('ALTER TABLE `images` ADD PRIMARY KEY (`id_image`);');
    $pdo->query('ALTER TABLE `images` MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT;');
    $pdo->query('ALTER TABLE `images` ADD FOREIGN KEY (`publisher_id`)
                                        REFERENCES `users` (`id_user`)
                                        ON DELETE CASCADE
                                        ON UPDATE CASCADE;');
    $pdo->query('CREATE TABLE `likes` (
                              `image_id` int(11) NOT NULL,
                              `liker_id` int(11) NOT NULL,
                              `created_at` TIMESTAMP NOT NULL DEFAULT NOW()
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    $pdo->query('ALTER TABLE `likes` ADD PRIMARY KEY (`image_id`,`liker_id`);');
    $pdo->query('ALTER TABLE `likes` ADD FOREIGN KEY (`liker_id`)
                                      REFERENCES `users` (`id_user`)
                                      ON DELETE CASCADE
                                      ON UPDATE CASCADE;');
    $pdo->query('ALTER TABLE `likes`ADD FOREIGN KEY (`image_id`)
                                        REFERENCES `images` (`id_image`)
                                        ON DELETE CASCADE
                                        ON UPDATE CASCADE;');
    $pdo->query('CREATE TABLE `comments` (
                              `image_id` int(11) NOT NULL,
                              `author_id` int(11) NOT NULL,
                              `comment` text NOT NULL,
                              `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                              `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE now()
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    $pdo->query('ALTER TABLE `comments` ADD PRIMARY KEY (`image_id`,`author_id`);');
    $pdo->query('ALTER TABLE `comments` ADD FOREIGN KEY (`author_id`)
                                      REFERENCES `users` (`id_user`)
                                      ON DELETE CASCADE
                                      ON UPDATE CASCADE;');

    $pdo->query('ALTER TABLE `comments` ADD FOREIGN KEY (`image_id`)
                                        REFERENCES `images` (`id_image`)
                                        ON DELETE CASCADE
                                        ON UPDATE CASCADE;');
                                        
  flashMessage('db_success','success','THE DATA BASE WAS INITIALIZED CORRECTLY');
}catch(PDOException $e)
{
  flasMessage('db_error','error','DB WAS NOT INITIALIZED CORRECTLY '.$e->getMessage());
  exit();
}
?>