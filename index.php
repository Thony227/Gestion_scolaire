
<?php
session_start();

// ===================== INITIALISATION GLOBALE DES VARIABLES SÉCURISÉES =====================
$search_query = $_GET['search_matricule'] ?? '';
$search_results = null;
$message = '';
$message_type = '';
$page = $_GET['page'] ?? 'home';
$role = $_SESSION['role'] ?? null;

$eleves = []; $classes = []; $enseignants = []; $classes_data = []; $eleves_list = [];
$situation_finance = []; $moyenne_result = null;
$absences_eleve = []; $sanctions_eleve = [];

// Nouvelles variables de filtrage
$niveau_filtre = $_GET['filtre_niveau'] ?? '';
$classe_filtre = isset($_GET['filtre_classe']) ? (int)$_GET['filtre_classe'] : 0;
$classes_filtrees = [];
$enseignants_list = [];
$emplois_enseignant = [];
$moyennes_liste = [];

// ===================== CONNEXION BDD MYSQL =====================
$host = 'localhost'; $dbname = 'edumada'; $username = 'root'; $password = '';

try {
    $db = new PDO("mysql:host=$host", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création automatique des tables
    $db->exec("
        CREATE TABLE IF NOT EXISTS utilisateurs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom_utilisateur VARCHAR(255) NOT NULL UNIQUE,
            mot_de_passe VARCHAR(255) NOT NULL,
            role VARCHAR(50) NOT NULL,
            est_connecte INT DEFAULT 0
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS classes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom_classe VARCHAR(255) NOT NULL,
            niveau VARCHAR(255) NOT NULL,
            titulaire_id INT DEFAULT NULL
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS enseignants (
            id INT AUTO_INCREMENT PRIMARY KEY,
            matricule VARCHAR(50) NOT NULL UNIQUE,
            nom VARCHAR(255) NOT NULL,
            matiere_principale VARCHAR(255) NOT NULL,
            classe_id INT,
            type_enseignant VARCHAR(100) DEFAULT 'Non-fixe',
            salaire_base_fixe DECIMAL(10,2) DEFAULT 0.00,
            volume_horaire_base INT DEFAULT 0,
            taux_horaire DECIMAL(10,2) DEFAULT 0.00,
            heures_travaillees INT DEFAULT 0,
            heures_sup INT DEFAULT 0,
            taux_horaire_sup DECIMAL(10,2) DEFAULT 0.00,
            FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE SET NULL
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS eleves (
            id INT AUTO_INCREMENT PRIMARY KEY,
            matricule VARCHAR(50) NOT NULL UNIQUE,
            nom VARCHAR(255) NOT NULL,
            age INT NOT NULL,
            classe_id INT,
            FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE SET NULL
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            eleve_id INT NOT NULL,
            examen_num INT NOT NULL DEFAULT 1,
            matiere VARCHAR(255) NOT NULL,
            note FLOAT NOT NULL,
            coefficient INT DEFAULT 1,
            FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS absences (
            id INT AUTO_INCREMENT PRIMARY KEY,
            eleve_id INT NOT NULL,
            date DATE NOT NULL,
            type VARCHAR(50) NOT NULL,
            justifie TINYINT(1) DEFAULT 0,
            FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS sanctions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            eleve_id INT NOT NULL,
            date DATE NOT NULL,
            type VARCHAR(100) NOT NULL,
            motif TEXT NOT NULL,
            FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS frais_scolarite (
            id INT AUTO_INCREMENT PRIMARY KEY,
            eleve_id INT NOT NULL,
            montant_total DECIMAL(10,2) NOT NULL,
            annee_scolaire VARCHAR(50) NOT NULL,
            FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS paiements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            frais_id INT NOT NULL,
            montant_paye DECIMAL(10,2) NOT NULL,
            date_paiement DATE NOT NULL,
            FOREIGN KEY (frais_id) REFERENCES frais_scolarite(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS pointages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            enseignant_id INT NOT NULL,
            date_pointage DATE NOT NULL,
            heure_arrivee TIME NOT NULL,
            heure_depart TIME NOT NULL,
            heures_effectuees DECIMAL(5,2) NOT NULL,
            FOREIGN KEY (enseignant_id) REFERENCES enseignants(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS demandes_mdp (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom_utilisateur VARCHAR(255) NOT NULL,
            nouveau_mdp VARCHAR(255) NOT NULL,
            statut VARCHAR(50) DEFAULT 'En attente'
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS depenses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            designation VARCHAR(255) NOT NULL,
            categorie VARCHAR(100) NOT NULL,
            quantite INT NOT NULL DEFAULT 1,
            prix_unitaire DECIMAL(10,2) NOT NULL,
            montant_total DECIMAL(10,2) NOT NULL,
            date_depense DATE NOT NULL,
            enregistre_par VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB;

        CREATE TABLE IF NOT EXISTS emplois_du_temps (
            id INT AUTO_INCREMENT PRIMARY KEY,
            enseignant_id INT NOT NULL,
            jour_semaine VARCHAR(50) NOT NULL,
            heure_debut TIME NOT NULL,
            heure_fin TIME NOT NULL,
            matiere VARCHAR(255) NOT NULL,
            classe_id INT,
            FOREIGN KEY (enseignant_id) REFERENCES enseignants(id) ON DELETE CASCADE,
            FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE SET NULL
        ) ENGINE=InnoDB;
    ");

    // ===================== MIGRATION DE SÉCURITÉ AUTOMATIQUE =====================
    $migrations = [
        "ALTER TABLE notes ADD COLUMN examen_num INT NOT NULL DEFAULT 1",
        "ALTER TABLE notes ADD COLUMN coefficient INT NOT NULL DEFAULT 1",
        "ALTER TABLE classes ADD COLUMN titulaire_id INT DEFAULT NULL",
        "ALTER TABLE enseignants ADD COLUMN type_enseignant VARCHAR(100) DEFAULT 'Non-fixe'",
        "ALTER TABLE enseignants ADD COLUMN salaire_base_fixe DECIMAL(10,2) DEFAULT 0.00",
        "ALTER TABLE enseignants ADD COLUMN volume_horaire_base INT DEFAULT 0",
        "ALTER TABLE enseignants ADD COLUMN taux_horaire DECIMAL(10,2) DEFAULT 0.00",
        "ALTER TABLE enseignants ADD COLUMN heures_travaillees INT DEFAULT 0",
        "ALTER TABLE enseignants ADD COLUMN heures_sup INT DEFAULT 0",
        "ALTER TABLE enseignants ADD COLUMN taux_horaire_sup DECIMAL(10,2) DEFAULT 0.00",
        "ALTER TABLE utilisateurs ADD COLUMN est_connecte INT DEFAULT 0",
        "ALTER TABLE pointages MODIFY COLUMN heure_depart TIME NULL DEFAULT NULL",
        "ALTER TABLE pointages MODIFY COLUMN heures_effectuees DECIMAL(5,2) NULL DEFAULT 0.00"
    ];
    foreach ($migrations as $sql) {
        try { $db->exec($sql); } catch (PDOException $e) {}
    }

    $checkUsers = $db->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
    if ($checkUsers == 0) {
        $db->exec("
            INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, role, est_connecte) VALUES 
            ('admin', 'admin123', 'directeur', 0),
            ('secretaire', 'sec123', 'secretariat', 0),
            ('prof', 'prof123', 'enseignant', 0),
            ('comptable', 'comp123', 'comptable', 0);
        ");
    }

} catch (PDOException $e) {
    die("Erreur de connexion ou d'initialisation de la BDD : " . $e->getMessage());
}

// ===================== MODULE DE RÉSOLUTION D'ID PAR RECHERCHE DIRECTE DE MATRICULE =====================
$auto_selected_student_id = null;
if (!empty($_GET['recherche_directe_matricule'])) {
    $stmt_find = $db->prepare("SELECT id, classe_id FROM eleves WHERE matricule = ? LIMIT 1");
    $stmt_find->execute([trim($_GET['recherche_directe_matricule'])]);
    $found_st = $stmt_find->fetch(PDO::FETCH_ASSOC);
    if ($found_st) {
        $_GET['eleve_id'] = $found_st['id'];
        $auto_selected_student_id = $found_st['id'];
        $classe_filtre = (int)$found_st['classe_id'];
        
        $stmt_cl_level = $db->prepare("SELECT niveau FROM classes WHERE id = ?");
        $stmt_cl_level->execute([$classe_filtre]);
        $niveau_filtre = $stmt_cl_level->fetchColumn() ?: '';
    } else {
        $message = "Aucun élève trouvé pour le matricule " . htmlspecialchars($_GET['recherche_directe_matricule']);
        $message_type = 'error';
    }
}

$auto_selected_teacher_id = null;
if (!empty($_GET['recherche_directe_matricule_prof'])) {
    $stmt_find = $db->prepare("SELECT id, classe_id FROM enseignants WHERE matricule = ? LIMIT 1");
    $stmt_find->execute([trim($_GET['recherche_directe_matricule_prof'])]);
    $found_ens = $stmt_find->fetch(PDO::FETCH_ASSOC);
    if ($found_ens) {
        $_GET['enseignant_id'] = $found_ens['id'];
        $auto_selected_teacher_id = $found_ens['id'];
        $classe_filtre = (int)$found_ens['classe_id'];
        
        $stmt_cl_level = $db->prepare("SELECT niveau FROM classes WHERE id = ?");
        $stmt_cl_level->execute([$classe_filtre]);
        $niveau_filtre = $stmt_cl_level->fetchColumn() ?: '';
    } else {
        $message = "Aucun enseignant trouvé pour le matricule " . htmlspecialchars($_GET['recherche_directe_matricule_prof']);
        $message_type = 'error';
    }
}

// ===================== FONCTION CALCUL PAIE DYNAMIQUE AVEC POINTAGE =====================
function calculer_paie_enseignant_complete(PDO $db, array $ens): array {
    $sal_base = (float)$ens['salaire_base_fixe'];
    $vol_prevu = (int)$ens['volume_horaire_base'];
    $taux_normal = (float)$ens['taux_horaire'];
    $heures_sup = (int)$ens['heures_sup'];
    $taux_sup = (float)$ens['taux_horaire_sup'];

    $stmt = $db->prepare("SELECT SUM(heures_effectuees) FROM pointages WHERE enseignant_id = ? AND heure_depart IS NOT NULL");
    $stmt->execute([$ens['id']]);
    $heures_pointees = $stmt->fetchColumn();

    $heures_eff = ($heures_pointees !== null) ? (float)$heures_pointees : (float)$ens['heures_travaillees'];

    $heures_manquantes = 0; $deduction = 0.00;
    if ($heures_eff < $vol_prevu) {
        $heures_manquantes = $vol_prevu - $heures_eff;
        $deduction = $heures_manquantes * $taux_normal;
    }

    $gain_normal = $sal_base - $deduction;
    $gain_sup = $heures_sup * $taux_sup;
    $salaire_final = $gain_normal + $gain_sup;

    if ($salaire_final < 0) { $salaire_final = 0; }

    return [
        'base' => $sal_base,
        'heures_manquantes' => $heures_manquantes,
        'deduction' => $deduction,
        'gain_normal' => $gain_normal,
        'gain_sup' => $gain_sup,
        'total' => $salaire_final,
        'heures_effectuees' => $heures_eff
    ];
}

// ===================== ACTIONS LOGIQUES DE GESTION BDD =====================
function connexion_login(PDO $db, string $nom, string $mdp): ?string {
    $stmt = $db->prepare("SELECT role FROM utilisateurs WHERE nom_utilisateur = ? AND mot_de_passe = ?");
    $stmt->execute([$nom, $mdp]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['role'] : null;
}

function ajouter_classe(PDO $db, string $nom, string $niveau, ?int $titulaire_id): void {
    $stmt = $db->prepare("INSERT INTO classes (nom_classe, niveau, titulaire_id) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $niveau, $titulaire_id ?: null]);
}

function modifier_classe(PDO $db, int $id, string $nom, string $niveau, ?int $titulaire_id): void {
    $stmt = $db->prepare("UPDATE classes SET nom_classe=?, niveau=?, titulaire_id=? WHERE id=?");
    $stmt->execute([$nom, $niveau, $titulaire_id ?: null, $id]);
}

function ajouter_enseignant(PDO $db, string $matricule, string $nom, string $matiere, ?int $classe_id, string $type, float $sal_base, int $vol, float $taux, int $h_trav, int $h_sup, float $t_sup): void {
    $stmt = $db->prepare("INSERT INTO enseignants (matricule, nom, matiere_principale, classe_id, type_enseignant, salaire_base_fixe, volume_horaire_base, taux_horaire, heures_travaillees, heures_sup, taux_horaire_sup) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$matricule, $nom, $matiere, $classe_id ?: null, $type, $sal_base, $vol, $taux, $h_trav, $h_sup, $t_sup]);
}

function modifier_enseignant(PDO $db, int $id, string $matricule, string $nom, string $matiere, ?int $classe_id, string $type, float $sal_base, int $vol, float $taux, int $h_trav, int $h_sup, float $t_sup): void {
    $stmt = $db->prepare("UPDATE enseignants SET matricule=?, nom=?, matiere_principale=?, classe_id=?, type_enseignant=?, salaire_base_fixe=?, volume_horaire_base=?, taux_horaire=?, heures_travaillees=?, heures_sup=?, taux_horaire_sup=? WHERE id=?");
    $stmt->execute([$matricule, $nom, $matiere, $classe_id ?: null, $type, $sal_base, $vol, $taux, $h_trav, $h_sup, $t_sup, $id]);
}

function ajouter_eleve(PDO $db, string $matricule, string $nom, int $age, int $classe_id): void {
    $db->prepare("INSERT INTO eleves (matricule, nom, age, classe_id) VALUES (?, ?, ?, ?)")->execute([$matricule, $nom, $age, $classe_id]);
}

function modifier_eleve(PDO $db, int $id, string $matricule, string $nom, int $age, int $classe_id): void {
    $db->prepare("UPDATE eleves SET matricule=?, nom=?, age=?, classe_id=? WHERE id=?")->execute([$matricule, $nom, $age, $classe_id, $id]);
}

function supprimer_eleve(PDO $db, int $id): void {
    $db->prepare("DELETE FROM eleves WHERE id=?")->execute([$id]);
}

function ajouter_note(PDO $db, int $eleve_id, int $examen_num, string $matiere, float $note, int $coefficient): void {
    $stmt = $db->prepare("INSERT INTO notes (eleve_id, examen_num, matiere, note, coefficient) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$eleve_id, $examen_num, $matiere, $note, $coefficient]);
}

function modifier_note(PDO $db, int $note_id, float $note, int $coefficient): void {
    $stmt = $db->prepare("UPDATE notes SET note = ?, coefficient = ? WHERE id = ?");
    $stmt->execute([$note, $coefficient, $note_id]);
}

function moyenne_eleve(PDO $db, int $eleve_id): string {
    $stmt = $db->prepare("SELECT note, coefficient FROM notes WHERE eleve_id = ?");
    $stmt->execute([$eleve_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_points = 0; $total_coeffs = 0;
    foreach ($rows as $r) {
        $total_points += ($r['note'] * $r['coefficient']);
        $total_coeffs += $r['coefficient'];
    }
    return $total_coeffs > 0 ? number_format($total_points / $total_coeffs, 2) : 'Aucune note';
}

function ajouter_absence(PDO $db, int $eleve_id, string $date, string $type, int $justifie): void {
    $db->prepare("INSERT INTO absences (eleve_id, date, type, justifie) VALUES (?, ?, ?, ?)")->execute([$eleve_id, $date, $type, $justifie]);
}

function ajouter_sanction(PDO $db, int $eleve_id, string $date, string $type, string $motif): void {
    $db->prepare("INSERT INTO sanctions (eleve_id, date, type, motif) VALUES (?, ?, ?, ?)")->execute([$eleve_id, $date, $type, $motif]);
}

function ajouter_frais(PDO $db, int $eleve_id, float $montant, string $annee): void {
    $db->prepare("INSERT INTO frais_scolarite (eleve_id, montant_total, annee_scolaire) VALUES (?, ?, ?)")->execute([$eleve_id, $montant, $annee]);
}

function ajouter_paiement(PDO $db, int $frais_id, float $montant, string $date): void {
    $db->prepare("INSERT INTO paiements (frais_id, montant_paye, date_paiement) VALUES (?, ?, ?)")->execute([$frais_id, $montant, $date]);
}

function ajouter_pointage(PDO $db, int $enseignant_id, string $date, string $arrivee, string $depart): void {
    $diff = (strtotime($depart) - strtotime($arrivee)) / 3600;
    $heures_effectuees = $diff > 0 ? round($diff, 2) : 0;
    $stmt = $db->prepare("INSERT INTO pointages (enseignant_id, date_pointage, heure_arrivee, heure_depart, heures_effectuees) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$enseignant_id, $date, $arrivee, $depart, $heures_effectuees]);
}

// ===================== TRAITEMENT DES SESSIONS POST =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $role = connexion_login($db, $_POST['nom'] ?? '', $_POST['mdp'] ?? '');
        if ($role) {
            $_SESSION['role'] = $role;
            $_SESSION['nom']  = $_POST['nom'];
            
            // Met à jour l'état de connexion de l'utilisateur
            $db->prepare("UPDATE utilisateurs SET est_connecte = 1 WHERE nom_utilisateur = ?")
               ->execute([$_SESSION['nom']]);
               
            header('Location: index.php');
            exit;
        } else {
            $message = "Identifiants incorrects.";
            $message_type = 'error';
        }
    }

    if ($action === 'forgot_password') {
        $nom_u = $_POST['nom_utilisateur'] ?? '';
        $nouveau_mdp = $_POST['nouveau_mdp'] ?? '';
        if ($nom_u && $nouveau_mdp) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE nom_utilisateur = ?");
            $stmt->execute([$nom_u]);
            if ($stmt->fetchColumn() > 0) {
                $stmt = $db->prepare("INSERT INTO demandes_mdp (nom_utilisateur, nouveau_mdp, statut) VALUES (?, ?, 'En attente')");
                $stmt->execute([$nom_u, $nouveau_mdp]);
                $message = "Demande envoyée. En attente de validation par l'administrateur.";
                $message_type = "success";
            } else {
                $message = "Nom d'utilisateur inexistant.";
                $message_type = "error";
            }
        }
    }

    if (!isset($_SESSION['role'])) {
        if ($action !== 'forgot_password' && $action !== 'login') {
            $message = "Vous devez être connecté.";
            $message_type = 'error';
        }
    } else {
        switch ($action) {
            case 'logout':
                if (isset($_SESSION['nom'])) {
                    // Met à jour l'état de déconnexion
                    $db->prepare("UPDATE utilisateurs SET est_connecte = 0 WHERE nom_utilisateur = ?")
                       ->execute([$_SESSION['nom']]);
                }
                session_destroy(); header('Location: index.php'); exit;

            case 'ajouter_classe':
                ajouter_classe($db, $_POST['nom_classe'], $_POST['niveau'], (int)($_POST['titulaire_id'] ?? 0));
                $message = "Classe ajoutée avec succès."; $message_type = 'success'; break;

            case 'modifier_classe':
                modifier_classe($db, (int)$_POST['classe_id'], $_POST['nom_classe'], $_POST['niveau'], (int)($_POST['titulaire_id'] ?? 0));
                $message = "Classe modifiée avec succès."; $message_type = 'success'; break;

            case 'ajouter_enseignant':
                ajouter_enseignant($db, $_POST['matricule'], $_POST['nom_enseignant'], $_POST['matiere_principale'], (int)($_POST['classe_id'] ?? 0), $_POST['type_enseignant'], (float)$_POST['salaire_base_fixe'], (int)$_POST['volume_horaire_base'], (float)$_POST['taux_horaire'], (int)$_POST['heures_travaillees'], (int)$_POST['heures_sup'], (float)$_POST['taux_horaire_sup']);
                $message = "Enseignant ajouté."; $message_type = 'success'; break;

            case 'modifier_enseignant':
                modifier_enseignant($db, (int)$_POST['enseignant_id'], $_POST['matricule'], $_POST['nom_enseignant'], $_POST['matiere_principale'], (int)($_POST['classe_id'] ?? 0), $_POST['type_enseignant'], (float)$_POST['salaire_base_fixe'], (int)$_POST['volume_horaire_base'], (float)$_POST['taux_horaire'], (int)$_POST['heures_travaillees'], (int)$_POST['heures_sup'], (float)$_POST['taux_horaire_sup']);
                $message = "Données modifiées."; $message_type = 'success'; break;

            case 'ajouter_eleve':
                ajouter_eleve($db, $_POST['matricule'], $_POST['nom_eleve'], (int)$_POST['age'], (int)$_POST['classe_id']);
                $message = "Élève ajouté."; $message_type = 'success'; break;

            case 'modifier_eleve':
                modifier_eleve($db, (int)$_POST['eleve_id'], $_POST['matricule'], $_POST['nom_eleve'], (int)$_POST['age'], (int)$_POST['classe_id']);
                $message = "Élève modifié."; $message_type = 'success'; break;

            case 'supprimer_eleve':
                supprimer_eleve($db, (int)$_POST['eleve_id']);
                $message = "Élève supprimé."; $message_type = 'success'; break;

            case 'ajouter_note':
                ajouter_note($db, (int)$_POST['eleve_id'], (int)$_POST['examen_num'], $_POST['matiere'], (float)$_POST['note'], (int)$_POST['coefficient']);
                $message = "Note ajoutée."; $message_type = 'success'; break;

            case 'modifier_note':
                modifier_note($db, (int)$_POST['note_id'], (float)$_POST['note'], (int)$_POST['coefficient']);
                $message = "Note mise à jour avec succès."; $message_type = 'success'; break;

            case 'ajouter_absence':
                ajouter_absence($db, (int)$_POST['eleve_id'], $_POST['date'], $_POST['type_absence'], (int)$_POST['justifie']);
                $message = "Absence enregistrée."; $message_type = 'success'; break;

            case 'ajouter_sanction':
                ajouter_sanction($db, (int)$_POST['eleve_id'], $_POST['date'], $_POST['type_sanction'], $_POST['motif']);
                $message = "Sanction enregistrée."; $message_type = 'success'; break;

            case 'ajouter_frais':
                ajouter_frais($db, (int)$_POST['eleve_id'], (float)$_POST['montant_total'], $_POST['annee_scolaire']);
                $message = "Frais ajoutés."; $message_type = 'success'; break;

            case 'ajouter_paiement':
                ajouter_paiement($db, (int)$_POST['frais_id'], (float)$_POST['montant_paye'], $_POST['date']);
                $message = "Paiement enregistré."; $message_type = 'success'; break;

            case 'ajouter_pointage':
                ajouter_pointage($db, (int)$_POST['enseignant_id'], $_POST['date_pointage'], $_POST['heure_arrivee'], $_POST['heure_depart']);
                $message = "Pointage enregistré."; $message_type = 'success'; break;

            case 'valider_demande_mdp':
                if ($role === 'directeur') {
                    $id_demande = (int)$_POST['id_demande'];
                    $stmt = $db->prepare("SELECT nom_utilisateur, nouveau_mdp FROM demandes_mdp WHERE id = ?");
                    $stmt->execute([$id_demande]);
                    $demande = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($demande) {
                        $db->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE nom_utilisateur = ?")
                           ->execute([$demande['nouveau_mdp'], $demande['nom_utilisateur']]);
                        $db->prepare("UPDATE demandes_mdp SET statut = 'Valide' WHERE id = ?")
                           ->execute([$id_demande]);
                        $message = "Mot de passe mis à jour et demande validée.";
                        $message_type = "success";
                    }
                }
                break;

            case 'rejeter_demande_mdp':
                if ($role === 'directeur') {
                    $id_demande = (int)$_POST['id_demande'];
                    $db->prepare("UPDATE demandes_mdp SET statut = 'Rejete' WHERE id = ?")
                       ->execute([$id_demande]);
                    $message = "Demande rejetée.";
                    $message_type = "success";
                }
                break;

            case 'creer_compte_securise':
                if ($role === 'directeur') {
                    $nom_u = $_POST['nom_utilisateur'] ?? '';
                    $mdp_u = $_POST['mot_de_passe'] ?? '';
                    $role_u = $_POST['role_compte'] ?? '';
                    if ($nom_u && $mdp_u && $role_u) {
                        $stmt = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE nom_utilisateur = ?");
                        $stmt->execute([$nom_u]);
                        if ($stmt->fetchColumn() == 0) {
                            $db->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, role, est_connecte) VALUES (?, ?, ?, 0)")
                               ->execute([$nom_u, $mdp_u, $role_u]);
                            $message = "Compte sécurisé généré avec succès.";
                            $message_type = "success";
                        } else {
                            $message = "Ce nom d'utilisateur existe déjà.";
                            $message_type = "error";
                        }
                    }
                }
                break;

            case 'ajouter_depense':
                if ($role === 'directeur' || $role === 'comptable') {
                    $designation = $_POST['designation'] ?? '';
                    $categorie = $_POST['categorie'] ?? '';
                    $quantite = (int)($_POST['quantite'] ?? 1);
                    $prix_unitaire = (float)($_POST['prix_unitaire'] ?? 0.00);
                    $montant_total = $quantite * $prix_unitaire;
                    $date_depense = $_POST['date_depense'] ?? date('Y-m-d');
                    $enregistre_par = $_SESSION['nom'] ?? 'Inconnu';

                    if ($designation && $categorie && $prix_unitaire > 0) {
                        $stmt = $db->prepare("INSERT INTO depenses (designation, categorie, quantite, prix_unitaire, montant_total, date_depense, enregistre_par) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$designation, $categorie, $quantite, $prix_unitaire, $montant_total, $date_depense, $enregistre_par]);
                        $message = "Achat d'inventaire ou dépense enregistré avec succès.";
                        $message_type = "success";
                    } else {
                        $message = "Veuillez remplir correctement tous les champs de l'achat.";
                        $message_type = "error";
                    }
                }
                break;

            case 'ajouter_emploi':
                if ($role === 'directeur' || $role === 'secretariat') {
                    $ens_id = (int)$_POST['enseignant_id'];
                    $jour = $_POST['jour_semaine'];
                    $h_deb = $_POST['heure_debut'];
                    $h_fin = $_POST['heure_fin'];
                    $mat = $_POST['matiere'];
                    $cl_id = (int)($_POST['classe_id'] ?? 0);
                    $stmt = $db->prepare("INSERT INTO emplois_du_temps (enseignant_id, jour_semaine, heure_debut, heure_fin, matiere, classe_id) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$ens_id, $jour, $h_deb, $h_fin, $mat, $cl_id ?: null]);
                    $message = "Créneau d'emploi du temps enregistré.";
                    $message_type = 'success';
                }
                break;

            case 'supprimer_emploi':
                if ($role === 'directeur' || $role === 'secretariat') {
                    $id_emp = (int)$_POST['id_emploi'];
                    $db->prepare("DELETE FROM emplois_du_temps WHERE id = ?")->execute([$id_emp]);
                    $message = "Créneau emploi du temps supprimé.";
                    $message_type = 'success';
                }
                break;

            case 'pointage_entree':
                if ($role === 'directeur' || $role === 'secretariat') {
                    $ens_id = (int)$_POST['enseignant_id'];
                    $date = $_POST['date_pointage'] ?? date('Y-m-d');
                    $arrivee = $_POST['heure_arrivee'] ?? date('H:i:s');
                    
                    $stmt = $db->prepare("SELECT COUNT(*) FROM pointages WHERE enseignant_id = ? AND date_pointage = ? AND heure_depart IS NULL");
                    $stmt->execute([$ens_id, $date]);
                    if ($stmt->fetchColumn() > 0) {
                        $message = "Cet enseignant possède déjà un pointage d'entrée actif pour aujourd'hui.";
                        $message_type = 'error';
                    } else {
                        $stmt = $db->prepare("INSERT INTO pointages (enseignant_id, date_pointage, heure_arrivee, heure_depart, heures_effectuees) VALUES (?, ?, ?, NULL, 0.00)");
                        $stmt->execute([$ens_id, $date, $arrivee]);
                        $message = "Pointage d'entrée enregistré avec succès.";
                        $message_type = 'success';
                    }
                }
                break;

            case 'pointage_sortie':
                if ($role === 'directeur' || $role === 'secretariat') {
                    $pointage_id = (int)$_POST['pointage_id'];
                    $depart = $_POST['heure_depart'] ?? date('H:i:s');
                    
                    $stmt = $db->prepare("SELECT heure_arrivee FROM pointages WHERE id = ?");
                    $stmt->execute([$pointage_id]);
                    $arrivee = $stmt->fetchColumn();
                    
                    if ($arrivee) {
                        $diff = (strtotime($depart) - strtotime($arrivee)) / 3600;
                        $heures_effectuees = $diff > 0 ? round($diff, 2) : 0;
                        
                        $stmt = $db->prepare("UPDATE pointages SET heure_depart = ?, heures_effectuees = ? WHERE id = ?");
                        $stmt->execute([$depart, $heures_effectuees, $pointage_id]);
                        $message = "Pointage de sortie validé. Total effectué : {$heures_effectuees}h.";
                        $message_type = 'success';
                    } else {
                        $message = "Impossible de récupérer le pointage d'entrée.";
                        $message_type = 'error';
                    }
                }
                break;
        }
    }
}

// ===================== REQUETES DE LECTURE GLOBALES =====================
$eleves      = $db->query("SELECT eleves.*, classes.nom_classe FROM eleves LEFT JOIN classes ON eleves.classe_id = classes.id ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$classes     = $db->query("SELECT * FROM classes ORDER BY nom_classe ASC")->fetchAll(PDO::FETCH_ASSOC);
$enseignants = $db->query("SELECT enseignants.*, classes.nom_classe FROM enseignants LEFT JOIN classes ON enseignants.classe_id = classes.id ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

$classes_data = $db->query("
    SELECT c.*, ens.nom as titulaire_nom 
    FROM classes c 
    LEFT JOIN enseignants ens ON c.titulaire_id = ens.id
    ORDER BY c.nom_classe ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Application dynamique du filtre Niveau -> Classe pour l'affichage visuel
$classes_filtrees = $classes_data;
if (!empty($niveau_filtre)) {
    $classes_filtrees = array_filter($classes_data, function($c) use ($niveau_filtre) {
        return $c['niveau'] === $niveau_filtre;
    });
}

// Filtrage de la liste des élèves
if ($classe_filtre > 0) {
    $stmt = $db->prepare("SELECT eleves.*, classes.nom_classe FROM eleves LEFT JOIN classes ON eleves.classe_id = classes.id WHERE eleves.classe_id = ? ORDER BY eleves.nom ASC");
    $stmt->execute([$classe_filtre]);
    $eleves_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif (!empty($niveau_filtre)) {
    $stmt = $db->prepare("SELECT eleves.*, classes.nom_classe FROM eleves LEFT JOIN classes ON eleves.classe_id = classes.id WHERE classes.niveau = ? ORDER BY eleves.nom ASC");
    $stmt->execute([$niveau_filtre]);
    $eleves_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $eleves_list = $eleves;
}

// Filtrage de la liste des enseignants
if ($classe_filtre > 0) {
    $stmt = $db->prepare("SELECT enseignants.*, classes.nom_classe FROM enseignants LEFT JOIN classes ON enseignants.classe_id = classes.id WHERE enseignants.classe_id = ? ORDER BY enseignants.nom ASC");
    $stmt->execute([$classe_filtre]);
    $enseignants_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif (!empty($niveau_filtre)) {
    $stmt = $db->prepare("SELECT enseignants.*, classes.nom_classe FROM enseignants LEFT JOIN classes ON enseignants.classe_id = classes.id WHERE classes.niveau = ? ORDER BY enseignants.nom ASC");
    $stmt->execute([$niveau_filtre]);
    $enseignants_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $enseignants_list = $enseignants;
}

// ===================== REQUETE RECHERCHE GLOBALE =====================
if (!empty($search_query)) {
    $stmt = $db->prepare("SELECT eleves.*, classes.nom_classe FROM eleves LEFT JOIN classes ON eleves.classe_id = classes.id WHERE eleves.matricule = ? OR eleves.nom LIKE ?");
    $stmt->execute([$search_query, "%$search_query%"]);
    $search_eleve = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($search_eleve) {
        $e_id = $search_eleve['id'];
        $st = $db->prepare("SELECT examen_num, matiere, note, coefficient FROM notes WHERE eleve_id = ? ORDER BY examen_num ASC"); $st->execute([$e_id]); $notes_s = $st->fetchAll(PDO::FETCH_ASSOC);
        $st = $db->prepare("SELECT date, type, justifie FROM absences WHERE eleve_id = ? ORDER BY date DESC"); $st->execute([$e_id]); $abs_s = $st->fetchAll(PDO::FETCH_ASSOC);
        $search_results = ['type' => 'eleve', 'details' => $search_eleve, 'notes' => $notes_s, 'absences' => $abs_s];
    } else {
        $stmt = $db->prepare("SELECT enseignants.*, classes.nom_classe FROM enseignants LEFT JOIN classes ON enseignants.classe_id = classes.id WHERE enseignants.matricule = ? OR enseignants.nom LIKE ?");
        $stmt->execute([$search_query, "%$search_query%"]);
        $search_enseignant = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($search_enseignant) { $search_results = ['type' => 'enseignant', 'details' => $search_enseignant]; }
    }
}

// ===================== LECTURE DES DONNÉES SÉLECTIVES =====================
if ($page === 'situation_financiere') {
    $eleve_id_get = isset($_GET['eleve_id']) ? (int)$_GET['eleve_id'] : 0;
    if ($eleve_id_get > 0) {
        $stmt = $db->prepare("
            SELECT fs.id, fs.montant_total, fs.annee_scolaire, COALESCE(SUM(p.montant_paye), 0) as total_paye, e.nom, e.matricule
            FROM frais_scolarite fs
            JOIN eleves e ON fs.eleve_id = e.id
            LEFT JOIN paiements p ON p.frais_id = fs.id
            WHERE fs.eleve_id = ?
            GROUP BY fs.id, fs.montant_total, fs.annee_scolaire, e.nom, e.matricule
        ");
        $stmt->execute([$eleve_id_get]);
        $situation_finance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($classe_filtre > 0) {
        $stmt = $db->prepare("
            SELECT fs.id, fs.montant_total, fs.annee_scolaire, COALESCE(SUM(p.montant_paye), 0) as total_paye, e.nom, e.matricule
            FROM frais_scolarite fs
            JOIN eleves e ON fs.eleve_id = e.id
            LEFT JOIN paiements p ON p.frais_id = fs.id
            WHERE e.classe_id = ?
            GROUP BY fs.id, fs.montant_total, fs.annee_scolaire, e.nom, e.matricule
        ");
        $stmt->execute([$classe_filtre]);
        $situation_finance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (!empty($niveau_filtre)) {
        $stmt = $db->prepare("
            SELECT fs.id, fs.montant_total, fs.annee_scolaire, COALESCE(SUM(p.montant_paye), 0) as total_paye, e.nom, e.matricule
            FROM frais_scolarite fs
            JOIN eleves e ON fs.eleve_id = e.id
            JOIN classes c ON e.classe_id = c.id
            LEFT JOIN paiements p ON p.frais_id = fs.id
            WHERE c.niveau = ?
            GROUP BY fs.id, fs.montant_total, fs.annee_scolaire, e.nom, e.matricule
        ");
        $stmt->execute([$niveau_filtre]);
        $situation_finance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $situation_finance = $db->query("
            SELECT fs.id, fs.montant_total, fs.annee_scolaire, COALESCE(SUM(p.montant_paye), 0) as total_paye, e.nom, e.matricule
            FROM frais_scolarite fs
            JOIN eleves e ON fs.eleve_id = e.id
            LEFT JOIN paiements p ON p.frais_id = fs.id
            GROUP BY fs.id, fs.montant_total, fs.annee_scolaire, e.nom, e.matricule
            ORDER BY fs.id DESC
            LIMIT 50
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($page === 'moyenne_eleve') {
    $eleve_id_get = isset($_GET['eleve_id']) ? (int)$_GET['eleve_id'] : 0;
    if ($eleve_id_get > 0) {
        $moyenne_result = moyenne_eleve($db, $eleve_id_get);
    } elseif ($classe_filtre > 0) {
        $stmt = $db->prepare("SELECT id, nom, matricule FROM eleves WHERE classe_id = ? ORDER BY nom ASC");
        $stmt->execute([$classe_filtre]);
        $students_class = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($students_class as $st) {
            $moyennes_liste[] = [
                'nom' => $st['nom'],
                'matricule' => $st['matricule'],
                'moyenne' => moyenne_eleve($db, $st['id'])
            ];
        }
    } elseif (!empty($niveau_filtre)) {
        $stmt = $db->prepare("SELECT e.id, e.nom, e.matricule, c.nom_classe FROM eleves e JOIN classes c ON e.classe_id = c.id WHERE c.niveau = ? ORDER BY e.nom ASC");
        $stmt->execute([$niveau_filtre]);
        $students_level = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($students_level as $st) {
            $moyennes_liste[] = [
                'nom' => $st['nom'],
                'matricule' => $st['matricule'],
                'classe' => $st['nom_classe'],
                'moyenne' => moyenne_eleve($db, $st['id'])
            ];
        }
    }
}

if ($page === 'lister_absences') {
    $eleve_id_get = isset($_GET['eleve_id']) ? (int)$_GET['eleve_id'] : 0;
    if ($eleve_id_get > 0) {
        $stmt = $db->prepare("SELECT a.date, a.type, a.justifie, e.nom, e.matricule FROM absences a JOIN eleves e ON a.eleve_id = e.id WHERE a.eleve_id = ? ORDER BY a.date DESC");
        $stmt->execute([$eleve_id_get]);
        $absences_eleve = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($classe_filtre > 0) {
        $stmt = $db->prepare("SELECT a.date, a.type, a.justifie, e.nom, e.matricule FROM absences a JOIN eleves e ON a.eleve_id = e.id WHERE e.classe_id = ? ORDER BY a.date DESC");
        $stmt->execute([$classe_filtre]);
        $absences_eleve = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (!empty($niveau_filtre)) {
        $stmt = $db->prepare("SELECT a.date, a.type, a.justifie, e.nom, e.matricule FROM absences a JOIN eleves e ON a.eleve_id = e.id JOIN classes c ON e.classe_id = c.id WHERE c.niveau = ? ORDER BY a.date DESC");
        $stmt->execute([$niveau_filtre]);
        $absences_eleve = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $absences_eleve = $db->query("SELECT a.date, a.type, a.justifie, e.nom, e.matricule FROM absences a JOIN eleves e ON a.eleve_id = e.id ORDER BY a.date DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($page === 'lister_sanctions') {
    $eleve_id_get = isset($_GET['eleve_id']) ? (int)$_GET['eleve_id'] : 0;
    if ($eleve_id_get > 0) {
        $stmt = $db->prepare("SELECT s.date, s.type, s.motif, e.nom, e.matricule FROM sanctions s JOIN eleves e ON s.eleve_id = e.id WHERE s.eleve_id = ? ORDER BY s.date DESC");
        $stmt->execute([$eleve_id_get]);
        $sanctions_eleve = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($classe_filtre > 0) {
        $stmt = $db->prepare("SELECT s.date, s.type, s.motif, e.nom, e.matricule FROM sanctions s JOIN eleves e ON s.eleve_id = e.id WHERE e.classe_id = ? ORDER BY s.date DESC");
        $stmt->execute([$classe_filtre]);
        $sanctions_eleve = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (!empty($niveau_filtre)) {
        $stmt = $db->prepare("SELECT s.date, s.type, s.motif, e.nom, e.matricule FROM sanctions s JOIN eleves e ON s.eleve_id = e.id JOIN classes c ON e.classe_id = c.id WHERE c.niveau = ? ORDER BY s.date DESC");
        $stmt->execute([$niveau_filtre]);
        $sanctions_eleve = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $sanctions_eleve = $db->query("SELECT s.date, s.type, s.motif, e.nom, e.matricule FROM sanctions s JOIN eleves e ON s.eleve_id = e.id ORDER BY s.date DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Chargement de l'emploi du temps de l'enseignant sélectionné
if ($page === 'emplois_du_temps' && isset($_GET['enseignant_id'])) {
    $stmt = $db->prepare("
        SELECT edt.*, c.nom_classe 
        FROM emplois_du_temps edt
        LEFT JOIN classes c ON edt.classe_id = c.id
        WHERE edt.enseignant_id = ?
        ORDER BY FIELD(edt.jour_semaine, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'), edt.heure_debut ASC
    ");
    $stmt->execute([(int)$_GET['enseignant_id']]);
    $emplois_enseignant = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Sélection des pointages en cours (sans heure de départ encore enregistrée)
$pointages_actifs = $db->query("
    SELECT p.*, e.nom, e.matricule 
    FROM pointages p 
    JOIN enseignants e ON p.enseignant_id = e.id 
    WHERE p.heure_depart IS NULL
    ORDER BY p.date_pointage DESC, p.heure_arrivee DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ===================== CHARGEMENT DE LA VUE HTML PRINCIPALE =====================
require_once 'view.php';