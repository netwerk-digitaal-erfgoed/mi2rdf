USE mi2rdf;

CREATE TABLE `datasets` (
  `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25,
  `guid` varchar(40) NOT NULL,
  `org_name` varchar(250) NOT NULL,
  `state` varchar(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `converted` timestamp NULL DEFAULT NULL,
  `graph_uri` varchar(250) NULL DEFAULT NULL,
  `organisation_id` INT NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `datasets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guid` (`guid`);
    
CREATE TABLE `organisations` (
  `id` INT NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `namespace` VARCHAR(255) NOT NULL,
  `namespaceid` VARCHAR(255) NOT NULL,
  `namespacedef` VARCHAR(255) NOT NULL,
  `triply_token` VARCHAR(255) NOT NULL,
  `triply_user` VARCHAR(30) NOT NULL,
  `triply_dataset` VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisations`
  ADD PRIMARY KEY (`id`);
  
CREATE TABLE `users` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT, 
  `username` VARCHAR(100) NOT NULL,
  `password_hash` VARCHAR(100) NOT NULL,
  `organisation_id` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE (`username`);

CREATE TABLE `id2guid` ( 
	`id` int(11) NOT NULL, 
	`adt_id` INT NOT NULL, 
	`GUID` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 

ALTER TABLE `id2guid` ADD UNIQUE( `id`, `adt_id`);

INSERT INTO `organisations` VALUES (0,"MI2RDF","https://www.archive.io/","","MI2RDF","mi2rdf");