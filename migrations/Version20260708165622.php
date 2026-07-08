<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260708165622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Conversion des tables de MyISAM vers InnoDB';
    }

    public function up(Schema $schema): void
    {
        // Tables parentes d'abord (référencées par les autres)
        $this->addSql('ALTER TABLE users ENGINE=InnoDB');
        $this->addSql('ALTER TABLE themes ENGINE=InnoDB');

        // Tables enfants ensuite (qui référencent users/themes)
        $this->addSql('ALTER TABLE subjects ENGINE=InnoDB');
        $this->addSql('ALTER TABLE likes ENGINE=InnoDB');
        $this->addSql('ALTER TABLE comments ENGINE=InnoDB');
        $this->addSql('ALTER TABLE reset_password_requests ENGINE=InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Retour à MyISAM (non recommandé, fourni pour la réversibilité)
        $this->addSql('ALTER TABLE reset_password_requests ENGINE=MyISAM');
        $this->addSql('ALTER TABLE comments ENGINE=MyISAM');
        $this->addSql('ALTER TABLE likes ENGINE=MyISAM');
        $this->addSql('ALTER TABLE subjects ENGINE=MyISAM');
        $this->addSql('ALTER TABLE themes ENGINE=MyISAM');
        $this->addSql('ALTER TABLE users ENGINE=MyISAM');
    }
}