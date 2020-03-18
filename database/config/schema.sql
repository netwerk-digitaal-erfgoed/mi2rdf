USE mi2rdf;

CREATE TABLE `datasets` (
  `id` int(11) NOT NULL,
  `guid` varchar(40) NOT NULL,
  `org_name` varchar(250) NOT NULL,
  `state` varchar(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `converted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `datasets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guid` (`guid`);

ALTER TABLE `datasets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;