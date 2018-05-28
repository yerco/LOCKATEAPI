<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180326125344 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lockate_nodes (id INT AUTO_INCREMENT NOT NULL, gateway_id INT DEFAULT NULL, node_id INT NOT NULL, node_summary LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', node_timestamp DATETIME NOT NULL, INDEX IDX_BD81F8EC577F8E00 (gateway_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lockate_gateway (id INT AUTO_INCREMENT NOT NULL, gateway_id INT NOT NULL, gateway_summary LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', gateway_timestamp DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lockate_sensor (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, sensor_id INT NOT NULL, sensor_description LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', input LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', output LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', INDEX IDX_A191BB29460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lockate_nodes ADD CONSTRAINT FK_BD81F8EC577F8E00 FOREIGN KEY (gateway_id) REFERENCES lockate_gateway (id)');
        $this->addSql('ALTER TABLE lockate_sensor ADD CONSTRAINT FK_A191BB29460D9FD7 FOREIGN KEY (node_id) REFERENCES lockate_nodes (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lockate_sensor DROP FOREIGN KEY FK_A191BB29460D9FD7');
        $this->addSql('ALTER TABLE lockate_nodes DROP FOREIGN KEY FK_BD81F8EC577F8E00');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE lockate_nodes');
        $this->addSql('DROP TABLE lockate_gateway');
        $this->addSql('DROP TABLE lockate_sensor');
    }
}
