<?php
try
{
    $pdo = new PDO(DSNC, DB_USER, DB_PASS);
    $pdo->query('CREATE DATABASE IF NOT EXISTS '.DB_NAME);
    $pdo->query('USE '.DB_NAME);

    $pdo->query("CREATE TABLE `users` (
                              `id_user` int(11) NOT NULL,
                              `name` varchar(40) NOT NULL,
                              `email` varchar(255) NOT NULL UNIQUE,
                              `login` varchar(25) NOT NULL,
                              `password` varchar(255) NOT NULL,
                              `status` int(1) NOT NULL DEFAULT 0,
                              `notif`  int(1) NOT NULL DEFAULT 1,
                              `token` varchar(255) NOT NULL,
                              `profile` varchar(255),
                              `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                              `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

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
                              `id_comment` int(11) NOT NULL,
                              `image_id` int(11) NOT NULL,
                              `author_id` int(11) NOT NULL,
                              `comment` text NOT NULL,
                              `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                              `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE now()
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    $pdo->query('ALTER TABLE `comments` ADD PRIMARY KEY (`id_comment`);');
    $pdo->query('ALTER TABLE `comments` MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT;');
    $pdo->query('ALTER TABLE `comments` ADD FOREIGN KEY (`author_id`)
                                      REFERENCES `users` (`id_user`)
                                      ON DELETE CASCADE
                                      ON UPDATE CASCADE;');

    $pdo->query('ALTER TABLE `comments` ADD FOREIGN KEY (`image_id`)
                                        REFERENCES `images` (`id_image`)
                                        ON DELETE CASCADE
                                        ON UPDATE CASCADE;');

    $pdo->query('INSERT INTO `users` (`id_user`, `name`, `email`, `login`, `password`, `status`, `notif`, `token`, `profile`, `created_at`, `updated_at`) VALUES
                (1, "mouad", "bhaya001@gmail.com", "mbhaya", "1aeda8576df1ffe8257c5c765c2871af7e6a9fd68ef6cccdea5cb3edfed306ebff2f71188dd767b575ecdcb43faa131ce48de4967b056681ee6ba4b120763e1e", 1, 1, "e0b9e51ce08f4f7e48e6131394b0c554299a2d0f980e5de8d977094566a76c9aba79e39b6965a11c6bb2b5099df4e9bc5742b8b40c72ff16f817a5ce26e8de9d", "uploads/160284216819987410845f896e38cde10.png", "2020-10-15 18:49:06", "2020-10-16 09:56:18"),
                (2, "anas", "abenani@gmail.com", "abenani", "1aeda8576df1ffe8257c5c765c2871af7e6a9fd68ef6cccdea5cb3edfed306ebff2f71188dd767b575ecdcb43faa131ce48de4967b056681ee6ba4b120763e1e", 1, 1, "e0b9e51ce08f4f7e48e6131394b0c664299a2d0f980e5de8d977094566a76c9aba79e39b6965a11c6bb2b5099df4e9bc5742b8b40c72ff16f817a5ce26e8de9d", "uploads/160355678417347605255f9455b049f2a.png", "2020-10-17 08:49:06", "2020-10-16 09:56:18"),
                (3, "mouad el", "moelaza@gmail.com", "moelaza", "1aeda8576df1ffe8257c5c765c2871af7e6a9fd68ef6cccdea5cb3edfed306ebff2f71188dd767b575ecdcb43faa131ce48de4967b056681ee6ba4b120763e1e", 1, 1, "e0b9e51ce08f4f7e48e6131394b0c774299a2d0f980e5de8d977094566a76c9aba79e39b6965a11c6bb2b5099df4e9bc5742b8b40c72ff16f817a5ce26e8de9d", "uploads/160284216819987410845f896e38cde10.png", "2020-10-17 08:49:27", "2020-10-16 09:56:18"),
                (4, "test", "test@gmail.com", "test", "1aeda8576df1ffe8257c5c765c2871af7e6a9fd68ef6cccdea5cb3edfed306ebff2f71188dd767b575ecdcb43faa131ce48de4967b056681ee6ba4b120763e1e", 1, 0, "e0b9e51ce08f4f7e48e6131394b0c884299a2d0f980e5de8d977094566a76c9aba79e39b6965a11c6bb2b5099df4e9bc5742b8b40c72ff16f817a5ce26e8de9d", "uploads/160388680410868172335f995ed4b6de6.png", "2020-10-17 08:49:06", "2020-10-17 08:25:36");');

    $pdo->query('INSERT INTO `images` (`id_image`, `path`, `publisher_id`, `is_profile`, `created_at`, `updated_at`) VALUES
                (1, "uploads/16027766699728831005f886e5d26872.png", 1, 1, "2020-10-16 09:56:08", "2020-10-16 09:56:08"),
                (2, "uploads/16027786081398283585f8875f03ee4b.png", 2, 0, "2020-10-16 10:29:17", "2020-10-16 10:29:17"),
                (3, "uploads/16027786162847044635f8875f87a613.png", 1, 0, "2020-10-16 10:29:32", "2020-10-16 10:29:32"),
                (4, "uploads/16028441727642535005f89760caa6ac.png", 2, 0, "2020-10-16 10:30:08", "2020-10-16 10:30:08"),
                (5, "uploads/16028442085070916045f897630eeabf.png", 1, 0, "2020-10-16 10:43:51", "2020-10-16 10:43:51"),
                (6, "uploads/16028450668174835225f89798ae82b6.png", 3, 0, "2020-10-16 10:44:26", "2020-10-16 10:44:26"),
                (7, "uploads/16028450779160458435f8979955e25e.png", 3, 0, "2020-10-16 10:44:37", "2020-10-16 10:44:37"),
                (8, "uploads/16028451558461500475f8979e3a4bc4.png", 1, 0, "2020-10-16 10:45:55", "2020-10-16 10:45:55"),
                (9, "uploads/16028451723016557325f8979f442277.png", 2, 0, "2020-10-16 10:46:12", "2020-10-16 10:46:12"),
                (10, "uploads/160284216819987410845f896e38cde10.png", 1, 0, "2020-10-16 11:44:00", "2020-10-16 11:44:00"),
                (11, "uploads/160284415719772934895f8975fd74208.png", 2, 0, "2020-10-16 11:56:18", "2020-10-16 11:56:18"),
                (12, "uploads/160284503114611545055f897967777ce.png", 3, 0, "2020-10-16 11:56:34", "2020-10-16 11:56:34"),
                (13, "uploads/160354549516767020025f942997cd4e5.png", 2, 0, "2020-10-16 10:30:08", "2020-10-16 10:30:08"),
                (14, "uploads/160354550613479391665f9429a22ce5a.png", 2, 0, "2020-10-16 10:30:08", "2020-10-16 10:30:08"),
                (15, "uploads/160354551116145381605f9429a7d6893.png", 1, 0, "2020-10-16 10:43:51", "2020-10-16 10:43:51"),
                (16, "uploads/160355678417347605255f9455b049f2a.png", 3, 1, "2020-10-16 10:44:26", "2020-10-16 10:44:26"),
                (17, "uploads/160378772915340683915f97dbd18300a.png", 3, 0, "2020-10-16 10:44:37", "2020-10-16 10:44:37"),
                (18, "uploads/160378818914105569535f97dd9de052c.png", 1, 0, "2020-10-16 10:45:55", "2020-10-16 10:45:55"),
                (19, "uploads/160388680410868172335f995ed4b6de6.png", 2, 1, "2020-10-16 10:46:12", "2020-10-16 10:46:12"),
                (20, "uploads/160389209619627067095f99738073d29.png", 1, 0, "2020-10-16 11:44:00", "2020-10-16 11:44:00"),
                (21, "uploads/160396210014561751295f9a84f410e35.png", 3, 0, "2020-10-16 10:44:37", "2020-10-16 10:44:37"),
                (22, "uploads/160396210712634630555f9a84fb3415f.png", 1, 0, "2020-10-16 10:45:55", "2020-10-16 10:45:55"),
                (23, "uploads/160405086117875413775f9bdfadedfdb.png", 2, 0, "2020-10-16 10:46:12", "2020-10-16 10:46:12"),
                (24, "uploads/160406098810770825245f9c073c79087.png", 2, 0, "2020-10-16 11:56:18", "2020-10-16 11:56:18");');

    $pdo->query('INSERT INTO `likes` (`image_id`, `liker_id`, `created_at`) VALUES
                (3, 1, "2020-10-17 07:54:21"),
                (3, 2, "2020-10-17 08:01:55"),
                (3, 3, "2020-10-17 08:02:27"),
                (3, 4, "2020-10-17 08:01:55"),
                (4, 1, "2020-10-17 07:54:21");');

    $pdo->query('INSERT INTO `comments` (`id_comment`, `image_id`, `author_id`, `comment`) VALUES
                (1, 3, 2, "test comment"),
                (2, 3, 3, "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."),
                (3, 3, 4, "consectetur adipiscing elit"),
                (4, 3, 1, "hi"),
                (5, 3, 1, "hello world");');
                                        
  flashMessage('db_success','success','THE DATA BASE WAS INITIALIZED CORRECTLY');
}catch(PDOException $e)
{
  flasMessage('db_error','error','DB WAS NOT INITIALIZED CORRECTLY '.$e->getMessage());
  exit();
}
?>