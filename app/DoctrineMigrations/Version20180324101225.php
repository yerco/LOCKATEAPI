<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180324101225 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lockate_sensor (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, sensor_id INT NOT NULL, sensor_description LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', input LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', output LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', INDEX IDX_A191BB29460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lockate_sensor ADD CONSTRAINT FK_A191BB29460D9FD7 FOREIGN KEY (node_id) REFERENCES lockate_nodes (id)');
        $this->addSql('ALTER TABLE lockate_nodes ADD node_summary LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', DROP analog_input, DROP analog_output, DROP digital_output, DROP digital_input, DROP txt');
        $this->addSql('ALTER TABLE lockate_gateway CHANGE gateway_description gateway_summary LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', CHANGE timestamp gateway_timestamp DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE lockate_sensor');
        $this->addSql('ALTER TABLE lockate_gateway CHANGE gateway_timestamp timestamp DATETIME NOT NULL, CHANGE gateway_summary gateway_description LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE lockate_nodes ADD analog_output LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', ADD digital_output LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', ADD digital_input LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', ADD txt LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', CHANGE node_summary analog_input LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\'');
    }
}
