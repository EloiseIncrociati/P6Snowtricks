<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927204728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ALTER label SET NOT NULL');
        $this->addSql('ALTER TABLE comment DROP trick_id');
        $this->addSql('ALTER TABLE comment DROP user_id');
        $this->addSql('ALTER TABLE comment ALTER content TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE comment ALTER content DROP DEFAULT');
        $this->addSql('ALTER TABLE comment ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE comment ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE media DROP trick_id');
        $this->addSql('ALTER TABLE trick ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trick ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trick DROP title');
        $this->addSql('ALTER TABLE trick DROP media');
        $this->addSql('ALTER TABLE trick DROP content');
        $this->addSql('ALTER TABLE trick DROP category_id');
        $this->addSql('ALTER TABLE trick DROP user_id');
        $this->addSql('ALTER TABLE trick ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE trick ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE trick ALTER updated_at SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN trick.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" DROP role');
        $this->addSql('ALTER TABLE "user" ALTER token SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category ALTER label DROP NOT NULL');
        $this->addSql('ALTER TABLE comment ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ALTER content TYPE TEXT');
        $this->addSql('ALTER TABLE comment ALTER content DROP DEFAULT');
        $this->addSql('ALTER TABLE comment ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE comment ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS NULL');
        $this->addSql('ALTER TABLE media ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trick ADD media VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trick ADD content TEXT NOT NULL');
        $this->addSql('ALTER TABLE trick ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick DROP name');
        $this->addSql('ALTER TABLE trick DROP description');
        $this->addSql('ALTER TABLE trick ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE trick ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE trick ALTER updated_at DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN trick.updated_at IS NULL');
        $this->addSql('ALTER TABLE "user" ADD role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER token DROP NOT NULL');
    }
}
