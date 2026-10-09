Migrations de la base de données (mises à jour depuis l'administration)
======================================================================
Un fichier <version>.php par version qui modifie la base (ex. 4.13.php).
Modèle et règles complètes : en-tête de backdoor/inc/sbuiadmin-update-db.php
et help.txt (section « Mises à jour »).
- IDEMPOTENT : uniquement les méthodes de SbMigration (addColumn, dropColumn,
  createTable, addIndex, addConfig...), qui vérifient l'état réel de la base.
- Toujours une partie 'down' (annulation) quand c'est possible.
- Suppression en deux temps : version N n'utilise plus, version N+1 supprime.
