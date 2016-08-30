
-- 
-- Modification table type abonnement pour supporter les modifications 2016-2017
-- 
ALTER TABLE `type_abonnement`
ADD `montant_15` int(11) NOT NULL AFTER `montant_10`,
ADD `valide_debut` year(4) NOT NULL,
ADD `valide_fin` year(4) NULL AFTER `valide_debut`,
ADD `is_archive` tinyint NULL DEFAULT '0';

UPDATE type_abonnement SET is_archive = 1;

-- 
-- Ajout du montant dans le détail de commande pour faciliter les calculs
-- 
ALTER TABLE `detail_commande` ADD `montant` decimal(10,2) NOT NULL;

UPDATE detail_commande dc SET dc.montant = (SELECT ta.montant_10 FROM type_abonnement ta WHERE ta.type_abonnement_id = dc.fk_type_abonnement);

-- 
-- Suppression des abos en vrac inutile pour 2016
-- 
DELETE FROM detail_commande WHERE fk_type_abonnement IN (SELECT type_abonnement_id FROM type_abonnement WHERE is_archive = 1) AND fk_commande = 278;