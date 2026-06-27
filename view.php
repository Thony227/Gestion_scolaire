
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSPCA - Système de Gestion Scolaire</title>
    <!-- Icône du site (Favicon) qui remplace le logo Wamp dans l'onglet -->
    <link rel="icon" type="image/jpeg" href="images/lycee_bg.jpg">
    <!-- Chargement des icônes Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Liaison avec la feuille de style externe CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (!$role): ?>
    <?php if ($page === 'forgot_password'): ?>
    <!-- ==================== PAGE MOT DE PASSE OUBLIÉ ==================== -->
    <div class="login-wrapper">
       <div class="login-box">
           <div class="logo-container">
               <img src="images/lycee_bg.jpg" alt="Logo de l'établissement">
           </div>
           <h1>RÉINITIALISATION</h1>
           <?php if ($message): ?>
               <div class="alert <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
           <?php endif; ?>
           <form method="POST">
               <input type="hidden" name="action" value="forgot_password">
               <div class="form-group" style="text-align:left">
                   <label>Nom d'utilisateur :</label>
                   <input type="text" name="nom_utilisateur" required autofocus placeholder="Ex: admin, secretaire...">
               </div>
               <div class="form-group" style="text-align:left">
                   <label>Nouveau mot de passe souhaité :</label>
                   <input type="password" name="nouveau_mdp" required placeholder="Saisissez votre nouveau mot de passe">
               </div>
               <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;"><i class="bi bi-send-fill"></i> Envoyer la demande</button>
               <a href="?page=home" class="btn btn-sm" style="display:block; margin-top:15px; color:#1a252f; text-decoration:none;"><i class="bi bi-arrow-left"></i> Retour à la connexion</a>
           </form>
       </div>
    </div>
    <?php else: ?>
    <!-- ==================== PAGE LOGIN ==================== -->
    <div class="login-wrapper">
       <div class="login-box">
           <!-- Logo profil arrondi moderne type avatar pointant vers le dossier images -->
           <div class="logo-container">
               <img src="images/lycee_bg.jpg" alt="Logo de connexion">
           </div>
           <h1>CONNEXION</h1>
           <?php if ($message): ?>
               <div class="alert <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
           <?php endif; ?>
           <form method="POST">
               <input type="hidden" name="action" value="login">
               <div class="form-group" style="text-align:left">
                   <label>Nom de l'utilisateur :</label>
                   <input type="text" name="nom" required autofocus>
               </div>
               
               <!-- Champ Mot de passe avec option Masquer/Afficher -->
               <div class="form-group" style="text-align:left; position: relative;">
                   <label>Mot de passe :</label>
                   <div style="position: relative;">
                       <input type="password" name="mdp" id="login-password" required style="padding-right: 40px;">
                       <span id="toggle-password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #2C3E50; font-size: 18px; display: flex; align-items: center;">
                           <i class="bi bi-eye-slash" id="toggle-icon"></i>
                       </span>
                   </div>
               </div>
               
               <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;"><i class="bi bi-box-arrow-in-right"></i> Se connecter</button>
               <!-- Lien Mot de passe oublié -->
               <a href="?page=forgot_password" style="display:block; margin-top:15px; font-size:13px; color:#3498db; text-decoration:none; font-weight:600;"><i class="bi bi-question-circle"></i> Mot de passe oublié ?</a>
           </form>
       </div>
    </div>
    <?php endif; ?>

<?php else: ?>
<!-- ==================== APP PRINCIPALE ==================== -->
<nav>
   <!-- Intégration du logo arrondi dans la Navbar -->
   <span class="brand" style="display: flex; align-items: center; gap: 8px;">
       <img src="images/lycee_bg.jpg" alt="Logo" style="width: 32px; height: 32px; object-fit: cover; border-radius: 50%; border: 2px solid #f1c40f;">
       LSPCA
   </span>
   
   <!-- Bouton Toggle pour le menu Hamburger Responsive -->
   <button class="nav-toggle" id="nav-toggle" aria-label="Ouvrir le menu">
       <i class="bi bi-list"></i>
   </button>
   
   <div class="nav-links" id="nav-links">
       <a href="?page=home" <?= $page==='home'?'class="active"':'' ?>><i class="bi bi-house-door-fill"></i> Accueil</a>
       <a href="?page=lister_eleves" <?= $page==='lister_eleves'?'class="active"':'' ?>><i class="bi bi-people-fill"></i> Élèves</a>
       <a href="?page=lister_classes" <?= $page==='lister_classes'?'class="active"':'' ?>><i class="bi bi-building-fill"></i> Classes</a>
       
       <?php if ($role !== 'enseignant' && $role !== 'comptable'): ?>
           <a href="?page=lister_enseignants" <?= $page==='lister_enseignants'?'class="active"':'' ?>><i class="bi bi-person-video3"></i> Enseignants</a>
           <a href="?page=pointage_enseignant" <?= $page==='pointage_enseignant'?'class="active"':'' ?>><i class="bi bi-clock-fill"></i> Pointage</a>
       <?php endif; ?>

       <!-- Masquage des onglets de bulletins, absences et sanctions pour le rôle comptable -->
       <?php if ($role !== 'comptable'): ?>
           <a href="?page=emplois_du_temps" <?= $page==='emplois_du_temps'?'class="active"':'' ?>><i class="bi bi-calendar3"></i> Emplois du temps</a>
           <a href="?page=bulletin_par_classe" <?= $page==='bulletin_par_classe'?'class="active"':'' ?>><i class="bi bi-printer-fill"></i> Bulletins</a>
           <a href="?page=lister_absences" <?= $page==='lister_absences'?'class="active"':'' ?>><i class="bi bi-calendar-check-fill"></i> Absences</a>
           <a href="?page=lister_sanctions" <?= $page==='lister_sanctions'?'class="active"':'' ?>><i class="bi bi-exclamation-triangle-fill"></i> Sanctions</a>
       <?php endif; ?>
       
       <a href="?page=moyenne_eleve" <?= $page==='moyenne_eleve'?'class="active"':'' ?>><i class="bi bi-bar-chart-line-fill"></i> Moyenne</a>
       
       <?php if ($role === 'directeur' || $role === 'comptable'): ?>
           <a href="?page=situation_financiere" <?= $page==='situation_financiere'?'class="active"':'' ?>><i class="bi bi-cash-coin"></i> Finances</a>
           <!-- Nouvelles liaisons : Inventaire & Historique -->
           <a href="?page=lister_inventaire" <?= $page==='lister_inventaire'?'class="active"':'' ?>><i class="bi bi-box-seam-fill"></i> Inventaire</a>
           <a href="?page=historique_financier" <?= $page==='historique_financier'?'class="active"':'' ?>><i class="bi bi-clock-history"></i> Historique</a>
       <?php endif; ?>
       
       <!-- Bouton Plein Écran manuel inséré dans la Navbar -->
       <a href="#" id="btn-fullscreen" style="background-color: #2c3e50; color: #f1c40f; font-weight: bold;"><i class="bi bi-fullscreen"></i> Plein Écran</a>
       
       <form method="POST" style="display:inline">
           <input type="hidden" name="action" value="logout">
           <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-power"></i> Déconnexion</button>
       </form>
   </div>
</nav>

<div class="container">
   <!-- RECHERCHE RAPIDE PAR MATRICULE -->
   <div class="search-container">
       <form method="GET" style="display:flex; gap:10px; align-items:center;">
           <input type="hidden" name="page" value="home">
           <label style="font-weight:bold; font-size:14px; white-space:nowrap;"><i class="bi bi-search"></i> Recherche par Matricule :</label>
           <input type="text" name="search_matricule" placeholder="Ex: ELV-2026-X..." value="<?= htmlspecialchars($search_query ?? '') ?>" style="flex:1; padding:8px 12px; border-radius:4px; border:none; color:black;">
           <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Recherche</button>
           <?php if (!empty($search_query)): ?>
               <a href="index.php" class="btn btn-danger btn-sm" style="text-decoration:none;"><i class="bi bi-x-circle"></i> Annuler</a>
           <?php endif; ?>
       </form>
   </div>

   <?php if ($message): ?>
       <div class="alert <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
   <?php endif; ?>

   <!-- RÉSULTAT RECHERCHE SANS target="_blank" -->
   <?php if ($search_results): ?>
       <div class="card" style="border: 1px dashed rgb(155, 219, 52);">
           <h2 style="color:rgb(155, 219, 52);"><i class="bi bi-check-circle-fill"></i> Résultat pour : <?= htmlspecialchars($search_query ?? '') ?></h2>
           <?php if ($search_results['type'] === 'eleve'): $det = $search_results['details']; ?>
               <p><strong>Élève :</strong> <?= htmlspecialchars($det['nom'] ?? '') ?> (<?= htmlspecialchars($det['matricule'] ?? '') ?>) | <strong>Classe :</strong> <?= htmlspecialchars($det['nom_classe'] ?? 'Non assignée') ?></p>
               
               <!-- Bloque l'impression des bulletins et fiches personnelles pour le comptable -->
               <?php if ($role !== 'comptable'): ?>
                   <div style="margin-top:10px; display:flex; gap:10px;">
                       <a href="?print=bulletin&eleve_id=<?= $det['id'] ?>" class="btn btn-success btn-sm"><i class="bi bi-printer-fill"></i> Imprimer Bulletin</a>
                       <a href="?print=fiche_eleve&eleve_id=<?= $det['id'] ?>" class="btn btn-orange btn-sm"><i class="bi bi-person-badge"></i> Imprimer Fiche Personnelle</a>
                   </div>
               <?php endif; ?>
           <?php elseif ($search_results['type'] === 'enseignant'): $det = $search_results['details']; ?>
               <?php if ($role !== 'enseignant' && $role !== 'comptable'): $paie = calculer_paie_enseignant_complete($db, $det); ?>
                   <p><strong>Enseignant :</strong> <?= htmlspecialchars($det['nom'] ?? '') ?></p>
                   <?php if ($role === 'directeur'): ?>
                       <p><strong>Salaire Net :</strong> <?= number_format($paie['total'], 2, ',', ' ') ?> Ar</p>
                   <?php endif; ?>
               <?php else: ?><p>Accès restreint aux données enseignants.</p><?php endif; ?>
           <?php endif; ?>
       </div>
   <?php endif; ?>

   <!-- ===================== STRUCTURE COMMUNE : BLOCS FILTRES ET ACCÈS MATRICULES ===================== -->
   <?php
   // Génération dynamique des blocs de filtrage HTML héritant des styles existants
   $html_filtre_eleve = '
   <div style="background:#1e2d3b; padding:15px; border-radius:6px; border:1px solid #4a6278; margin-bottom:20px; display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
       <form method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; flex:1; margin:0;">
           <input type="hidden" name="page" value="'.htmlspecialchars($page).'">
           
           <div style="flex:1; min-width:120px;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#f1c40f;">1. Niveau :</label>
               <select name="filtre_niveau" style="width:100%; padding:6px; border-radius:4px; color:#2C3E50;" onchange="this.form.submit()">
                   <option value="">-- Tous --</option>
                   <option value="Primaire" '.($niveau_filtre === "Primaire" ? "selected" : "").'>Primaire</option>
                   <option value="Collège" '.($niveau_filtre === "Collège" ? "selected" : "").'>Collège</option>
                   <option value="Lycée" '.($niveau_filtre === "Lycée" ? "selected" : "").'>Lycée</option>
               </select>
           </div>
           
           <div style="flex:1.2; min-width:150px;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#f1c40f;">2. Classe :</label>
               <select name="filtre_classe" style="width:100%; padding:6px; border-radius:4px; color:#2C3E50;" onchange="this.form.submit()">
                   <option value="0">-- Toutes --</option>';
                   foreach ($classes_filtrees as $cl) {
                       $html_filtre_eleve .= '<option value="'.$cl['id'].'" '.($classe_filtre === (int)$cl['id'] ? "selected" : "").'>'.htmlspecialchars($cl['nom_classe']).'</option>';
                   }
   $html_filtre_eleve .= '
               </select>
           </div>
           
           <div style="flex:1.8; min-width:180px;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#f1c40f;">3. Sélectionner l\'élève :</label>
               <select name="eleve_id" style="width:100%; padding:6px; border-radius:4px; color:#2C3E50;">
                   <option value="0">-- Choisir --</option>';
                   foreach ($eleves_list as $e) {
                       $sel = (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === (int)$e['id']) || ($auto_selected_student_id === (int)$e['id']) ? "selected" : "";
                       $html_filtre_eleve .= '<option value="'.$e['id'].'" '.$sel.'>'.htmlspecialchars($e['matricule'])." - ".htmlspecialchars($e['nom']).'</option>';
                   }
   $html_filtre_eleve .= '
               </select>
           </div>
           
           <button type="submit" class="btn btn-primary" style="margin:0; padding:7px 15px;"><i class="bi bi-funnel-fill"></i> Filtrer</button>
       </form>

       <form method="GET" style="display:flex; gap:8px; align-items:flex-end; border-left:1px dashed #4a6278; padding-left:15px; min-width:260px; margin:0;">
           <input type="hidden" name="page" value="'.htmlspecialchars($page).'">
           <div style="flex:1;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#3498db;"><i class="bi bi-search"></i> Matricule Élève :</label>
               <input type="text" name="recherche_directe_matricule" placeholder="Ex: ELV-2026-..." value="'.htmlspecialchars($_GET['recherche_directe_matricule'] ?? '').'" style="width:100%; padding:6px; border-radius:4px; color:black; border:1px solid #ccc;">
           </div>
           <button type="submit" class="btn btn-orange" style="margin:0; padding:7px 12px;"><i class="bi bi-lightning-fill"></i> Accéder</button>
       </form>
   </div>';

   $html_filtre_enseignant = '
   <div style="background:#1e2d3b; padding:15px; border-radius:6px; border:1px solid #4a6278; margin-bottom:20px; display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
       <form method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; flex:1; margin:0;">
           <input type="hidden" name="page" value="'.htmlspecialchars($page).'">
           
           <div style="flex:1; min-width:120px;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#f1c40f;">1. Niveau :</label>
               <select name="filtre_niveau" style="width:100%; padding:6px; border-radius:4px; color:#2C3E50;" onchange="this.form.submit()">
                   <option value="">-- Tous --</option>
                   <option value="Primaire" '.($niveau_filtre === "Primaire" ? "selected" : "").'>Primaire</option>
                   <option value="Collège" '.($niveau_filtre === "Collège" ? "selected" : "").'>Collège</option>
                   <option value="Lycée" '.($niveau_filtre === "Lycée" ? "selected" : "").'>Lycée</option>
               </select>
           </div>
           
           <div style="flex:1.2; min-width:150px;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#f1c40f;">2. Classe :</label>
               <select name="filtre_classe" style="width:100%; padding:6px; border-radius:4px; color:#2C3E50;" onchange="this.form.submit()">
                   <option value="0">-- Toutes --</option>';
                   foreach ($classes_filtrees as $cl) {
                       $html_filtre_enseignant .= '<option value="'.$cl['id'].'" '.($classe_filtre === (int)$cl['id'] ? "selected" : "").'>'.htmlspecialchars($cl['nom_classe']).'</option>';
                   }
   $html_filtre_enseignant .= '
               </select>
           </div>
           
           <div style="flex:1.8; min-width:180px;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#f1c40f;">3. Enseignant ciblé :</label>
               <select name="enseignant_id" style="width:100%; padding:6px; border-radius:4px; color:#2C3E50;">
                   <option value="0">-- Choisir --</option>';
                   foreach ($enseignants_list as $ens) {
                       $sel = (isset($_GET['enseignant_id']) && (int)$_GET['enseignant_id'] === (int)$ens['id']) || ($auto_selected_teacher_id === (int)$ens['id']) ? "selected" : "";
                       $html_filtre_enseignant .= '<option value="'.$ens['id'].'" '.$sel.'>'.htmlspecialchars($ens['matricule'])." - ".htmlspecialchars($ens['nom']).'</option>';
                   }
   $html_filtre_enseignant .= '
               </select>
           </div>
           
           <button type="submit" class="btn btn-primary" style="margin:0; padding:7px 15px;"><i class="bi bi-funnel-fill"></i> Filtrer</button>
       </form>

       <form method="GET" style="display:flex; gap:8px; align-items:flex-end; border-left:1px dashed #4a6278; padding-left:15px; min-width:260px; margin:0;">
           <input type="hidden" name="page" value="'.htmlspecialchars($page).'">
           <div style="flex:1;">
               <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:4px; color:#3498db;"><i class="bi bi-search"></i> Matricule Enseignant :</label>
               <input type="text" name="recherche_directe_matricule_prof" placeholder="Ex: ENS-2026-..." value="'.htmlspecialchars($_GET['recherche_directe_matricule_prof'] ?? '').'" style="width:100%; padding:6px; border-radius:4px; color:black; border:1px solid #ccc;">
           </div>
           <button type="submit" class="btn btn-orange" style="margin:0; padding:7px 12px;"><i class="bi bi-lightning-fill"></i> Accéder</button>
       </form>
   </div>';
   ?>

   <!-- ROUTAGE DES PAGES INTERNES -->
   <?php if ($page === 'home'): ?>
       
       <!-- EN-TÊTE DU DASHBOARD RE-POSITIONNÉ DANS LE BODY : Statut au centre, Paramètres Admin aligné à droite (Responsive) -->
       <div class="dashboard-header">
           <div class="header-left"></div>
           <div class="header-center">
               <span class="badge badge-<?= $role ?>">
                   <span class="status-dot"></span>
                   <?= $role ?>
               </span>
           </div>
           <div class="header-right">
               <?php if ($role === 'directeur'): ?>
                   <a href="?page=parametres_admin" class="btn-param"><i class="bi bi-gear-fill"></i> Paramètres Admin</a>
               <?php endif; ?>
           </div>
       </div>

      <center><h1>Bienvenue au Lycée Saint Pierre Canisius</h1></center>
      <div class="menu-grid">
          <a class="menu-item" href="?page=lister_eleves"><span class="icon"><i class="bi bi-people-fill"></i></span>Fiches élèves (Par Classe)</a>
          <a class="menu-item" href="?page=lister_classes"><span class="icon"><i class="bi bi-building"></i></span>Liste des classes</a>
          
          <!-- Masque l'accès aux bulletins, absences et sanctions sur le Dashboard pour le comptable -->
          <?php if ($role !== 'comptable'): ?>
              <a class="menu-item" href="?page=emplois_du_temps"><span class="icon"><i class="bi bi-calendar3"></i></span>Emplois du temps</a>
              <a class="menu-item" href="?page=bulletin_par_classe"><span class="icon"><i class="bi bi-printer-fill"></i></span>Bulletins par classe</a>
              <a class="menu-item" href="?page=lister_absences"><span class="icon"><i class="bi bi-calendar-check-fill"></i></span>Absences d'un élève</a>
              <a class="menu-item" href="?page=lister_sanctions"><span class="icon"><i class="bi bi-exclamation-triangle-fill"></i></span>Sanctions d'un élève</a>
          <?php endif; ?>

          <?php if ($role !== 'enseignant' && $role !== 'comptable'): ?>
              <a class="menu-item" href="?page=lister_enseignants"><span class="icon"><i class="bi bi-person-vcard-fill"></i></span>Liste des enseignants</a>
              <a class="menu-item" href="?page=pointage_enseignant"><span class="icon"><i class="bi bi-clock-fill"></i></span>Pointage (Entrée/Sortie)</a>
          <?php endif; ?>

          <?php if ($role === 'directeur'): ?>
              <a class="menu-item" href="?page=ajouter_classe"><span class="icon"><i class="bi bi-plus-square-fill"></i></span>Ajouter une classe</a>
              <a class="menu-item" href="?page=ajouter_enseignant"><span class="icon"><i class="bi bi-person-fill-add"></i></span>Ajouter un enseignant</a>
              <a class="menu-item" href="?page=modifier_enseignant"><span class="icon"><i class="bi bi-person-fill-gear"></i></span>Modifier un enseignant</a>
              <a class="menu-item" href="?page=ajouter_frais"><span class="icon"><i class="bi bi-wallet2"></i></span>Attribuer frais</a>
              <a class="menu-item" href="?page=ajouter_paiement"><span class="icon"><i class="bi bi-credit-card"></i></span>Ajouter paiement</a>
              <a class="menu-item" href="?page=situation_financiere"><span class="icon"><i class="bi bi-cash-stack"></i></span>Situation financière</a>
              <a class="menu-item" href="?page=ajouter_sanction"><span class="icon"><i class="bi bi-shield-fill-exclamation"></i></span>Sanctionner élève</a>
              <!-- Ajout des cartes de raccourci pour Comptable & Directeur -->
              <a class="menu-item" href="?page=lister_inventaire"><span class="icon"><i class="bi bi-box-seam-fill"></i></span>Inventaire / Dépenses</a>
              <a class="menu-item" href="?page=historique_financier"><span class="icon"><i class="bi bi-clock-history"></i></span>Historique Financier</a>
          <?php endif; ?>
          
          <?php if ($role === 'comptable'): ?>
              <a class="menu-item" href="?page=ajouter_frais"><span class="icon"><i class="bi bi-wallet2"></i></span>Attribuer frais</a>
              <a class="menu-item" href="?page=ajouter_paiement"><span class="icon"><i class="bi bi-credit-card"></i></span>Ajouter paiement</a>
              <a class="menu-item" href="?page=situation_financiere"><span class="icon"><i class="bi bi-cash-stack"></i></span>Situation financière</a>
              <a class="menu-item" href="?page=lister_inventaire"><span class="icon"><i class="bi bi-box-seam-fill"></i></span>Inventaire / Dépenses</a>
              <a class="menu-item" href="?page=historique_financier"><span class="icon"><i class="bi bi-clock-history"></i></span>Historique Financier</a>
          <?php endif; ?>
          
          <?php if ($role === 'secretariat'): ?>
              <a class="menu-item" href="?page=ajouter_eleve"><span class="icon"><i class="bi bi-person-plus-fill"></i></span>Ajouter un élève</a>
              <a class="menu-item" href="?page=modifier_eleve"><span class="icon"><i class="bi bi-person-dash-fill"></i></span>Modifier un élève</a>
              <a class="menu-item" href="?page=supprimer_eleve"><span class="icon"><i class="bi bi-trash-fill"></i></span>Supprimer un élève</a>
              <a class="menu-item" href="?page=ajouter_absence"><span class="icon"><i class="bi bi-calendar-plus-fill"></i></span>Ajouter une absence</a>
              <a class="menu-item" href="?page=ajouter_sanction"><span class="icon"><i class="bi bi-shield-fill-exclamation"></i></span>Sanctionner élève</a>
          <?php endif; ?>
          <?php if ($role === 'enseignant'): ?>
              <a class="menu-item" href="?page=ajouter_note"><span class="icon"><i class="bi bi-file-earmark-plus-fill"></i></span>Saisir une note</a>
              <a class="menu-item" href="?page=modifier_note"><span class="icon"><i class="bi bi-pencil-square"></i></span>Modifier une note</a>
              <a class="menu-item" href="?page=ajouter_absence"><span class="icon"><i class="bi bi-calendar-plus-fill"></i></span>Ajouter une absence</a>
              <a class="menu-item" href="?page=ajouter_sanction"><span class="icon"><i class="bi bi-shield-fill-exclamation"></i></span>Sanctionner élève</a>
          <?php endif; ?>
      </div>

   <?php elseif ($page === 'lister_eleves'): ?>
       <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
           <h2>👥 Fiches et Actions Élèves (Filtres Dynamiques)</h2>
           <div style="display:flex; gap:10px; align-items:center; flex-wrap: wrap;">
               <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
               <a href="?print=liste_eleves&filtre_classe=<?= $classe_filtre ?>" class="btn btn-success btn-sm"><i class="bi bi-printer"></i> Imprimer la liste filtrée</a>
           </div>
       </div>
       
       <!-- Intégration du filtre unifié sur l'onglet Liste Élèves -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <?php if ($eleves_list): ?>
           <div class="table-responsive">
               <table>
                   <thead>
                       <tr><th style="width: 80px;">N° Table</th><th>Matricule</th><th>Nom Complet</th><th>Âge</th><th>Classe</th><th>Actions associées</th></tr>
                   </thead>
                   <tbody>
                   <?php $num_local = 1; foreach ($eleves_list as $e): ?>
                       <tr <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $e['id']) ? 'style="background-color:#1e3d2f;"' : '' ?>>
                           <td class="text-center" style="font-weight:bold; background:#1a252f; color:rgb(155, 219, 52);"><?= $num_local++ ?></td>
                           <td><strong><?= htmlspecialchars($e['matricule']) ?></strong></td>
                           <td><?= htmlspecialchars($e['nom']) ?></td>
                           <td><?= $e['age'] ?> ans</td>
                           <td><?= htmlspecialchars($e['nom_classe'] ?? 'Non affectée') ?></td>
                           <td style="display:flex; gap:5px; flex-wrap:wrap;">
                               <?php if ($role !== 'comptable'): ?>
                                   <a href="?print=bulletin&eleve_id=<?= $e['id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-file-pdf"></i> Bulletin</a>
                                   <a href="?print=fiche_eleve&eleve_id=<?= $e['id'] ?>" class="btn btn-orange btn-sm"><i class="bi bi-person-vcard"></i> Fiche</a>
                                   <a href="?page=lister_absences&eleve_id=<?= $e['id'] ?>" class="btn btn-sm" style="background-color:#16a085; color:white;"><i class="bi bi-calendar-event"></i> Absences</a>
                                   <a href="?page=lister_sanctions&eleve_id=<?= $e['id'] ?>" class="btn btn-danger btn-sm"><i class="bi bi-shield-exclamation"></i> Sanctions</a>
                               <?php endif; ?>
                               <?php if ($role === 'directeur' || $role === 'comptable'): ?>
                                   <a href="?page=situation_financiere&eleve_id=<?= $e['id'] ?>" class="btn btn-success btn-sm"><i class="bi bi-cash-stack"></i> Finances</a>
                               <?php endif; ?>
                           </td>
                       </tr>
                   <?php endforeach; ?>
                   </tbody>
               </table>
           </div>
           <?php else: ?><p style="color:#95a5a6">Aucun élève trouvé avec les filtres sélectionnés.</p><?php endif; ?>
       </div>

   <?php elseif ($page === 'lister_classes'): ?>
       <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
           <h2>🏫 Liste des classes</h2>
           <div>
               <a href="?page=home" class="btn btn-danger btn-sm" style="margin-right:5px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
               <?php if ($role === 'directeur'): ?>
                   <a href="?page=ajouter_classe" class="btn btn-primary btn-sm" style="margin-right:5px;"><i class="bi bi-plus"></i> Créer classe</a>
                   <a href="?page=modifier_classe" class="btn btn-orange btn-sm"><i class="bi bi-pencil-square"></i> Modifier classe</a>
               <?php endif; ?>
           </div>
       </div>
       <div class="card">
           <?php if ($classes_data): ?>
           <div class="table-responsive">
               <table>
                   <thead><tr><th>ID</th><th>Nom de la classe</th><th>Niveau</th><th>Enseignant Titulaire (Principal)</th></tr></thead>
                   <tbody>
                   <?php foreach ($classes_data as $c): ?>
                       <tr>
                           <td><?= $c['id'] ?></td>
                           <td><strong><?= htmlspecialchars($c['nom_classe']) ?></strong></td>
                           <td><?= htmlspecialchars($c['niveau']) ?></td>
                           <td style="color:rgb(155, 219, 52); font-weight:bold;"><i class="bi bi-person-fill-lock"></i> <?= htmlspecialchars($c['titulaire_nom'] ?? 'Aucun titulaire') ?></td>
                       </tr>
                   <?php endforeach; ?>
                   </tbody>
               </table>
           </div>
           <?php endif; ?>
       </div>

   <?php elseif ($page === 'ajouter_classe' && $role === 'directeur'): ?>
       <h2>➕ Ajouter une nouvelle classe</h2>
       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_classe">
               <div class="form-group"><label>Nom de la classe :</label><input type="text" name="nom_classe" placeholder="Ex: 6ème A, Seconde C..." required></div>
               <div class="form-group">
                   <label>Niveau / Cycle :</label>
                   <select name="niveau" required><option value="Primaire">Primaire</option><option value="Collège">Collège</option><option value="Lycée">Lycée</option></select>
               </div>
               <div class="form-group">
                   <label>Enseignant Titulaire (Adviser) :</label>
                   <select name="titulaire_id">
                       <option value="">-- Aucun --</option>
                       <?php foreach ($enseignants as $ens): ?><option value="<?= $ens['id'] ?>"><?= htmlspecialchars($ens['nom']) ?></option><?php endforeach; ?>
                   </select>
               </div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Créer la classe</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'modifier_classe' && $role === 'directeur'): ?>
       <h2>✏️ Modifier une classe existante</h2>
       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="modifier_classe">
               <div class="form-group">
                   <label>Sélectionner la classe :</label>
                   <select name="classe_id" required>
                       <option value="">-- Choisir la classe --</option>
                       <?php foreach ($classes_data as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom_classe']) ?></option><?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Nouveau nom de classe :</label><input type="text" name="nom_classe" required></div>
               <div class="form-group">
                   <label>Niveau :</label>
                   <select name="niveau" required><option value="Primaire">Primaire</option><option value="Collège">Collège</option><option value="Lycée">Lycée</option></select>
               </div>
               <div class="form-group">
                   <label>Nouveau Titulaire :</label>
                   <select name="titulaire_id">
                       <option value="">-- Aucun --</option>
                       <?php foreach ($enseignants as $ens): ?><option value="<?= $ens['id'] ?>"><?= htmlspecialchars($ens['nom']) ?></option><?php endforeach; ?>
                   </select>
               </div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Enregistrer modifications</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'ajouter_enseignant' && $role === 'directeur'): ?>
       <h2>➕ Ajouter un enseignant</h2>
       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_enseignant">
               <div class="form-group"><label>Matricule :</label><input type="text" name="matricule" value="ENS-<?= date('Y') ?>-<?= rand(100, 999) ?>" required></div>
               <div class="form-group">
                   <label>Type d'Enseignant :</label>
                   <select name="type_enseignant" required>
                       <option value="Fixe (Primaire - Titulaire de classe)">Fixe (Primaire - Titulaire de classe)</option>
                       <option value="Non-fixe (Collège / Lycée - Intervenant)">Non-fixe (Collège / Lycée - Intervenant)</option>
                   </select>
               </div>
               <div class="form-group"><label>Nom complet :</label><input type="text" name="nom_enseignant" required></div>
               <div class="form-group"><label>Matière principale :</label><input type="text" name="matiere_principale" required></div>
               <div class="form-group">
                   <label>Classe assignée (Optionnel) :</label>
                   <select name="classe_id">
                       <option value="">-- Aucune --</option>
                       <?php foreach ($classes_data as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom_classe']) ?></option><?php endforeach; ?>
                   </select>
               </div>
               <div style="border: 1px solid #3498DB; padding: 15px; border-radius: 4px; margin-top:20px;">
                   <div class="form-group"><label>Salaire de base fixe (Ar) :</label><input type="number" step="0.01" name="salaire_base_fixe" value="600000" required></div>
                   <div class="form-group"><label>Volume horaire prévu (Heures) :</label><input type="number" name="volume_horaire_base" value="80" required></div>
                   <div class="form-group"><label>Taux de déduction par heure manquante (Ar/h) :</label><input type="number" step="0.01" name="taux_horaire" value="7500" required></div>
                   <div class="form-group"><label>Heures effectuées par défaut (Sans pointage) :</label><input type="number" name="heures_travaillees" value="80" required></div>
                   <div class="form-group"><label>Heures supplémentaires :</label><input type="number" name="heures_sup" value="0" required></div>
                   <div class="form-group"><label>Taux d'heures sup (Ar/h) :</label><input type="number" step="0.01" name="taux_horaire_sup" value="10000" required></div>
               </div>
               <button type="submit" class="btn btn-primary" style="margin-top:20px;"><i class="bi bi-plus-circle"></i> Enregistrer</button>
               <a href="?page=home" class="btn btn-danger" style="margin-top:20px; margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'modifier_enseignant' && $role === 'directeur'): ?>
       <h2>✏️ Modifier un enseignant</h2>
       
       <!-- Filtre enseignant unifié -->
       <?= $html_filtre_enseignant ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="modifier_enseignant">
               <div class="form-group">
                   <label>Sélectionner l'enseignant :</label>
                   <select name="enseignant_id" required>
                       <option value="">-- Choisir --</option>
                       <?php foreach ($enseignants as $ens): ?>
                           <option value="<?= $ens['id'] ?>" <?= (isset($_GET['enseignant_id']) && (int)$_GET['enseignant_id'] === $ens['id']) ? 'selected' : '' ?>><?= htmlspecialchars($ens['matricule']) ?> - <?= htmlspecialchars($ens['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Nouveau Matricule :</label><input type="text" name="matricule" required></div>
               <div class="form-group">
                   <label>Type d'Enseignant :</label>
                   <select name="type_enseignant" required>
                       <option value="Fixe (Primaire - Titulaire de classe)">Fixe (Primaire - Titulaire de classe)</option>
                       <option value="Non-fixe (Collège / Lycée - Intervenant)">Non-fixe (Collège / Lycée - Intervenant)</option>
                   </select>
               </div>
               <div class="form-group"><label>Nom complet :</label><input type="text" name="nom_enseignant" required></div>
               <div class="form-group"><label>Matière :</label><input type="text" name="matiere_principale" required></div>
               <div class="form-group">
                   <label>Classe :</label>
                   <select name="classe_id">
                       <option value="">-- Aucune --</option>
                       <?php foreach ($classes_data as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom_classe']) ?></option><?php endforeach; ?>
                   </select>
               </div>
               <div style="border: 1px solid #3498DB; padding: 15px; border-radius: 4px; margin-top:20px;">
                   <div class="form-group"><label>Salaire de base (Ar) :</label><input type="number" step="0.01" name="salaire_base_fixe" required></div>
                   <div class="form-group"><label>Volume horaire prévu :</label><input type="number" name="volume_horaire_base" required></div>
                   <div class="form-group"><label>Taux déduction :</label><input type="number" step="0.01" name="taux_horaire" required></div>
                   <div class="form-group"><label>Heures effectuées par défaut :</label><input type="number" name="heures_travaillees" required></div>
                   <div class="form-group"><label>Heures sup :</label><input type="number" name="heures_sup" required></div>
                   <div class="form-group"><label>Taux heures sup (Ar) :</label><input type="number" step="0.01" name="taux_horaire_sup" required></div>
               </div>
               <button type="submit" class="btn btn-primary" style="margin-top:20px;"><i class="bi bi-save"></i> Enregistrer</button>
               <a href="?page=home" class="btn btn-danger" style="margin-top:20px; margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'lister_enseignants' && $role !== 'enseignant' && $role !== 'comptable'): ?>
       <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
           <h2>👨‍🏫 Gestion des Enseignants (Filtres Dynamiques)</h2>
           <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
               <a href="?page=home" class="btn btn-danger btn-sm" style="margin-right:5px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
               <?php if ($role === 'directeur'): ?>
                   <a href="?print=liste_enseignants" class="btn btn-success btn-sm"><i class="bi bi-printer"></i> Imprimer État de Paie</a>
               <?php endif; ?>
           </div>
       </div>

       <!-- Filtre enseignant unifié -->
       <?= $html_filtre_enseignant ?>

       <div class="card">
           <?php if ($enseignants_list): ?>
           <div class="table-responsive">
               <table>
                   <thead>
                       <tr>
                           <th>Matricule</th><th>Nom</th><th>Matière</th><th>Classe</th><th>Type</th><th>Actions</th>
                           <?php if ($role === 'directeur'): ?>
                               <th>Salaire Base</th><th>Suivi Horaire</th><th>Déduction</th><th>Heures Sup</th><th>Net Calculé</th>
                           <?php endif; ?>
                       </tr>
                   </thead>
                   <tbody>
                   <?php foreach ($enseignants_list as $ens): $paie = calculer_paie_enseignant_complete($db, $ens); ?>
                       <tr <?= (isset($_GET['enseignant_id']) && (int)$_GET['enseignant_id'] === $ens['id']) ? 'style="background-color:#1e3d2f;"' : '' ?>>
                           <td><strong><?= htmlspecialchars($ens['matricule']) ?></strong></td>
                           <td><?= htmlspecialchars($ens['nom']) ?></td>
                           <td><?= htmlspecialchars($ens['matiere_principale']) ?></td>
                           <td><?= htmlspecialchars($ens['nom_classe'] ?? 'Non affectée') ?></td>
                           <td><span style="font-size:12px; font-weight:bold; color:#f1c40f;"><?= htmlspecialchars($ens['type_enseignant']) ?></span></td>
                           <td style="display:flex; gap:5px; flex-wrap:wrap;">
                               <a href="?page=emplois_du_temps&enseignant_id=<?= $ens['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-calendar3"></i> Emploi</a>
                               <a href="?page=pointage_enseignant" class="btn btn-sm btn-success"><i class="bi bi-clock"></i> Pointage</a>
                           </td>
                           <?php if ($role === 'directeur'): ?>
                               <td><?= number_format($paie['base'], 0, ',', ' ') ?> Ar</td>
                               <td><?= $paie['heures_effectuees'] ?>h / <?= $ens['volume_horaire_base'] ?>h</td>
                               <td style="color:#e74c3c; font-weight:bold;"><?= $paie['deduction'] > 0 ? '-' . number_format($paie['deduction'], 0, ',', ' ') : '0' ?> Ar</td>
                               <td>+<?= $ens['heures_sup'] ?>h</td>
                               <td style="font-weight:bold; color:rgb(155, 219, 52);"><?= number_format($paie['total'], 2, ',', ' ') ?> Ar</td>
                           <?php endif; ?>
                       </tr>
                   <?php endforeach; ?>
                   </tbody>
               </table>
           </div>
           <?php else: ?><p style="color:#95a5a6;">Aucun enseignant trouvé avec ces filtres.</p><?php endif; ?>
       </div>

   <?php elseif ($page === 'pointage_enseignant' && $role !== 'enseignant' && $role !== 'comptable'): ?>
       <h2>📅 Suivi du travail des enseignants & Pointages d'entrée/sortie</h2>
       
       <!-- Filtre enseignant unifié -->
       <?= $html_filtre_enseignant ?>

       <div class="card" style="border-left: 5px solid #2ecc71;">
           <h3 style="margin-bottom:15px;">⏱️ Saisie d'Entrée / Sortie instantanée</h3>
           <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
               
               <div style="border-right: 1px dashed #4a6278; padding-right:15px;">
                   <h4>📥 Enregistrer une Entrée (Arrivée)</h4>
                   <form method="POST" style="margin-top:10px;">
                       <input type="hidden" name="action" value="pointage_entree">
                       <div class="form-group">
                           <label>Enseignant arrivant :</label>
                           <select name="enseignant_id" required>
                               <option value="">-- Choisir --</option>
                               <?php foreach ($enseignants as $ens): ?>
                                   <option value="<?= $ens['id'] ?>" <?= (isset($_GET['enseignant_id']) && (int)$_GET['enseignant_id'] === $ens['id']) ? 'selected' : '' ?>><?= htmlspecialchars($ens['nom']) ?></option>
                               <?php endforeach; ?>
                           </select>
                       </div>
                       <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                           <div class="form-group">
                               <label>Date :</label>
                               <input type="date" name="date_pointage" value="<?= date('Y-m-d') ?>" required>
                           </div>
                           <div class="form-group">
                               <label>Heure d'arrivée :</label>
                               <input type="time" name="heure_arrivee" value="<?= date('H:i') ?>" required>
                           </div>
                       </div>
                       <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-box-arrow-in-right"></i> Marquer Entrée</button>
                   </form>
               </div>

               <div>
                   <h4>📤 Départs en attente de sortie</h4>
                   <div class="table-responsive" style="margin-top:10px;">
                       <table>
                           <thead>
                               <tr><th>Professeur</th><th>Entrée enregistrée</th><th>Action</th></tr>
                           </thead>
                           <tbody>
                               <?php if (!empty($pointages_actifs)): foreach ($pointages_actifs as $pa): ?>
                                   <tr <?= (isset($_GET['enseignant_id']) && (int)$_GET['enseignant_id'] === $pa['enseignant_id']) ? 'style="background-color:#1e3d2f;"' : '' ?>>
                                       <td><strong><?= htmlspecialchars($pa['nom']) ?></strong></td>
                                       <td><?= date('d/m/Y', strtotime($pa['date_pointage'])) ?> à <?= date('H:i', strtotime($pa['heure_arrivee'])) ?></td>
                                       <td>
                                           <form method="POST" style="display:flex; gap:5px; align-items:center;">
                                               <input type="hidden" name="action" value="pointage_sortie">
                                               <input type="hidden" name="pointage_id" value="<?= $pa['id'] ?>">
                                               <input type="time" name="heure_depart" value="<?= date('H:i') ?>" required style="padding:4px; font-size:12px; width:75px; color:black;">
                                               <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 8px; font-size:12px;"><i class="bi bi-box-arrow-right"></i> Sortie</button>
                                           </form>
                                       </td>
                                   </tr>
                               <?php endforeach; else: ?>
                                   <tr><td colspan="3" class="text-center" style="color:#7f8c8d; font-size:12px;">Aucun cours actif / pointage d'entrée ouvert.</td></tr>
                               <?php endif; ?>
                           </tbody>
                       </table>
                   </div>
               </div>

           </div>
       </div>

       <div class="card" style="margin-top:20px;">
           <h3>✍️ Saisie manuelle rétrospective</h3>
           <form method="POST" style="margin-top:15px;">
               <input type="hidden" name="action" value="ajouter_pointage">
               <div class="form-group">
                   <label>Sélectionner l'Enseignant :</label>
                   <select name="enseignant_id" required>
                       <option value="">-- Choisir --</option>
                       <?php foreach ($enseignants as $ens): ?>
                           <option value="<?= $ens['id'] ?>" <?= (isset($_GET['enseignant_id']) && (int)$_GET['enseignant_id'] === $ens['id']) ? 'selected' : '' ?>><?= htmlspecialchars($ens['matricule']) ?> - <?= htmlspecialchars($ens['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Date de la journée :</label><input type="date" name="date_pointage" value="<?= date('Y-m-d') ?>" required></div>
               <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                   <div class="form-group"><label>Heure d'arrivée :</label><input type="time" name="heure_arrivee" required></div>
                   <div class="form-group"><label>Heure de départ :</label><input type="time" name="heure_depart" required></div>
               </div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Enregistrer Pointage Manuel</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <!-- NOUVELLE VUE : EMPLOI DU TEMPS DES ENSEIGNANTS -->
   <?php elseif ($page === 'emplois_du_temps' && $role !== 'comptable'): ?>
       <h2>📅 Emplois du temps hebdomadaire des enseignants</h2>
       
       <!-- Filtre enseignant unifié -->
       <?= $html_filtre_enseignant ?>

       <div class="card">
           <div style="margin-bottom: 15px;">
               <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </div>

           <?php if (isset($_GET['enseignant_id'])): $sel_ens_id = (int)$_GET['enseignant_id']; ?>
               
               <?php if ($role === 'directeur' || $role === 'secretariat'): ?>
                   <div style="border: 1px dashed #3498db; padding: 15px; border-radius: 4px; margin-top:20px; background-color:#1e2d3b;">
                       <h4 style="color:#3498db;"><i class="bi bi-calendar-plus-fill"></i> Ajouter un créneau d'enseignement</h4>
                       <form method="POST" style="margin-top:10px;">
                           <input type="hidden" name="action" value="ajouter_emploi">
                           <input type="hidden" name="enseignant_id" value="<?= $sel_ens_id ?>">
                           <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:10px; flex-wrap:wrap;">
                               <div class="form-group">
                                   <label>Jour de semaine :</label>
                                   <select name="jour_semaine" required>
                                       <option value="Lundi">Lundi</option>
                                       <option value="Mardi">Mardi</option>
                                       <option value="Mercredi">Mercredi</option>
                                       <option value="Jeudi">Jeudi</option>
                                       <option value="Vendredi">Vendredi</option>
                                       <option value="Samedi">Samedi</option>
                                   </select>
                               </div>
                               <div class="form-group"><label>Heure Début :</label><input type="time" name="heure_debut" required></div>
                               <div class="form-group"><label>Heure Fin :</label><input type="time" name="heure_fin" required></div>
                               <div class="form-group"><label>Matière :</label><input type="text" name="matiere" required placeholder="Physique, Math..."></div>
                               <div class="form-group">
                                   <label>Classe assignée :</label>
                                   <select name="classe_id">
                                       <option value="">-- Aucune --</option>
                                       <?php foreach ($classes_data as $cl): ?><option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['nom_classe']) ?></option><?php endforeach; ?>
                                   </select>
                               </div>
                           </div>
                           <button type="submit" class="btn btn-primary btn-sm" style="margin-top:10px;"><i class="bi bi-plus-circle"></i> Confirmer</button>
                       </form>
                   </div>
               <?php endif; ?>

               <h3 style="margin-top:25px; margin-bottom:15px;"><i class="bi bi-grid-3x3-gap-fill"></i> Grille des Cours</h3>
               <div class="table-responsive">
                   <table>
                       <thead>
                           <tr><th>Jour</th><th>Plage Horaire</th><th>Matière</th><th>Classe assignée</th><th>Action</th></tr>
                       </thead>
                       <tbody>
                           <?php if ($emplois_enseignant): foreach ($emplois_enseignant as $emp): ?>
                               <tr>
                                   <td><strong><?= htmlspecialchars($emp['jour_semaine']) ?></strong></td>
                                   <td><?= date('H:i', strtotime($emp['heure_debut'])) ?> - <?= date('H:i', strtotime($emp['heure_fin'])) ?></td>
                                   <td><?= htmlspecialchars($emp['matiere']) ?></td>
                                   <td><strong><?= htmlspecialchars($emp['nom_classe'] ?? 'Non assignée') ?></strong></td>
                                   <td>
                                       <?php if ($role === 'directeur' || $role === 'secretariat'): ?>
                                           <form method="POST" onsubmit="return confirm('Retirer ce créneau de l\'emploi du temps ?')">
                                               <input type="hidden" name="action" value="supprimer_emploi">
                                               <input type="hidden" name="id_emploi" value="<?= $emp['id'] ?>">
                                               <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                           </form>
                                       <?php else: ?>
                                           <span style="font-style:italic; font-size:12px; color:#95a5a6;">Restreint</span>
                                       <?php endif; ?>
                                   </td>
                               </tr>
                           <?php endforeach; else: ?>
                               <tr><td colspan="5" class="text-center" style="color:#95a5a6;">Aucun créneau planifié pour le moment.</td></tr>
                           <?php endif; ?>
                       </tbody>
                   </table>
               </div>

           <?php endif; ?>
       </div>

   <!-- Sécurisation : Page bulletin inaccessible pour le comptable -->
   <?php elseif ($page === 'bulletin_par_classe' && $role !== 'comptable'): ?>
       <h2>📋 Impression des Bulletins par Classe</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <div style="margin-bottom: 15px;">
               <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </div>
           
           <?php if ($classe_filtre > 0 && !empty($eleves_list)): ?>
               <div style="margin-top:20px; margin-bottom:15px;">
                   <a href="?print=bulletins_classe&classe_id=<?= $classe_filtre ?>" class="btn btn-success"><i class="bi bi-printer-fill"></i> Imprimer tous les bulletins de cette classe</a>
               </div>
               <div class="table-responsive">
                   <table style="margin-top:10px;">
                       <thead><tr><th style="width: 80px;">N°</th><th>Matricule</th><th>Nom complet</th><th class="text-center">Impression</th></tr></thead>
                       <tbody>
                       <?php $num_c = 1; foreach ($eleves_list as $el): ?>
                           <tr <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $el['id']) ? 'style="background-color:#1e3d2f;"' : '' ?>>
                               <td class="text-center"><strong><?= $num_c++ ?></strong></td>
                               <td><?= htmlspecialchars($el['matricule']) ?></td>
                               <td><strong><?= htmlspecialchars($el['nom']) ?></strong></td>
                               <td class="text-center">
                                   <a href="?print=bulletin&eleve_id=<?= $el['id'] ?>" class="btn btn-success btn-sm" style="margin-right:5px;"><i class="bi bi-printer"></i> Bulletin</a>
                                   <a href="?print=fiche_eleve&eleve_id=<?= $el['id'] ?>" class="btn btn-orange btn-sm"><i class="bi bi-person-vcard"></i> Fiche</a>
                               </td>
                           </tr>
                       <?php endforeach; ?>
                       </tbody>
                   </table>
               </div>
           <?php endif; ?>
       </div>

   <?php elseif ($page === 'ajouter_frais' && ($role === 'directeur' || $role === 'comptable')): ?>
       <h2>💵 Paramétrer les frais de scolarité obligatoires</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_frais">
               <div class="form-group">
                   <label>Sélectionner l'élève :</label>
                   <select name="eleve_id" required>
                       <option value="">-- Choisir --</option>
                       <?php foreach ($eleves as $e): ?>
                           <option value="<?= $e['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $e['id']) ? 'selected' : '' ?>><?= htmlspecialchars($e['matricule']) ?> - <?= htmlspecialchars($e['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Montant annuel (Ar) :</label><input type="number" name="montant_total" step="0.01" required></div>
               <div class="form-group"><label>Année scolaire :</label><input type="text" name="annee_scolaire" required value="2026-2027"></div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-wallet"></i> Enregistrer</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'ajouter_paiement' && ($role === 'directeur' || $role === 'comptable')): ?>
       <h2>💳 Enregistrer un versement (Paiement)</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_paiement">
               <div class="form-group">
                   <label>Fiche de frais (Élève & Scolarité) :</label>
                   <select name="frais_id" required>
                       <option value="">-- Choisir la fiche --</option>
                       <?php 
                       $frais_list = $db->query("SELECT fs.id, fs.montant_total, fs.annee_scolaire, e.nom, e.matricule, e.id as eleve_id FROM frais_scolarite fs JOIN eleves e ON fs.eleve_id = e.id")->fetchAll(PDO::FETCH_ASSOC);
                       foreach ($frais_list as $f): ?>
                           <option value="<?= $f['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $f['eleve_id']) ? 'selected' : '' ?>><?= htmlspecialchars($f['matricule']) ?> - <?= htmlspecialchars($f['nom']) ?> (<?= number_format($f['montant_total'],0,',',' ') ?> Ar)</option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Montant versé ce jour (Ar) :</label><input type="number" name="montant_paye" step="0.01" required></div>
               <div class="form-group"><label>Date de paiement :</label><input type="date" name="date" required value="<?= date('Y-m-d') ?>"></div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-currency-exchange"></i> Confirmer</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'situation_financiere' && ($role === 'directeur' || $role === 'comptable')): ?>
       <h2>💰 Situation financière des élèves</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <div style="margin-bottom: 15px;">
               <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </div>
           <?php if (!empty($situation_finance)): ?>
               <?php foreach ($situation_finance as $f): $reste = $f['montant_total'] - ($f['total_paye'] ?? 0); ?>
                   <div class="finance-row" style="margin-top:20px; border:1px solid #4a6278; padding: 15px; border-radius: 4px; <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $f['id']) ? 'background-color:#1e3d2f;' : '' ?>">
                       <div>
                           <span><strong>Élève : <?= htmlspecialchars($f['nom']) ?> (<?= htmlspecialchars($f['matricule']) ?>)</strong></span><br>
                           <span>Année scolaire : <?= htmlspecialchars($f['annee_scolaire']) ?></span><br>
                           <span>Scolarité annuelle : <strong><?= number_format($f['montant_total'], 0, ',', ' ') ?> Ar</strong></span>
                       </div>
                       <div style="text-align: right;">
                           <span class="finance-paye">Déjà réglé : <?= number_format($f['total_paye'] ?? 0, 0, ',', ' ') ?> Ar</span><br>
                           <span class="finance-reste">Reste dû : <?= number_format($reste, 0, ',', ' ') ?> Ar</span>
                       </div>
                       <div style="width:100%; margin-top:10px; text-align:right;">
                           <a href="?print=recu&frais_id=<?= $f['id'] ?>" class="btn btn-success btn-sm"><i class="bi bi-receipt-cutoff"></i> Reçu officiel (PDF)</a>
                       </div>
                   </div>
               <?php endforeach; ?>
           <?php endif; ?>
       </div>

   <!-- NOUVELLE VUE: PARAMÈTRES ADMINISTRATEUR SÉCURISÉS (ONGLET UNIQUE) -->
   <?php elseif ($page === 'parametres_admin' && $role === 'directeur'): ?>
       <h2>⚙️ Paramètres Généraux & Sécurité</h2>
       
       <div class="card">
           <h3 style="margin-bottom:15px;">👥 Liste des Utilisateurs & Statut de Connexion</h3>
           <div class="table-responsive">
               <table>
                   <thead>
                       <tr><th>Nom de l'utilisateur</th><th>Rôle / Permissions</th><th>Statut de connexion</th></tr>
                   </thead>
                   <tbody>
                       <?php 
                       $utilisateurs_list = $db->query("SELECT nom_utilisateur, role, est_connecte FROM utilisateurs ORDER BY nom_utilisateur ASC")->fetchAll(PDO::FETCH_ASSOC);
                       foreach ($utilisateurs_list as $user): ?>
                           <tr>
                               <td><strong><?= htmlspecialchars($user['nom_utilisateur']) ?></strong></td>
                               <td><span class="badge badge-<?= $user['role'] ?>"><?= htmlspecialchars($user['role']) ?></span></td>
                               <td>
                                   <?php if ($user['est_connecte']): ?>
                                       <span style="display:inline-block; width:10px; height:10px; background-color:#2ece73; border-radius:50%; margin-right:5px; vertical-align:middle;"></span> <strong style="color:#2ece73;">En ligne</strong>
                                   <?php else: ?>
                                       <span style="display:inline-block; width:10px; height:10px; background-color:#95a5a6; border-radius:50%; margin-right:5px; vertical-align:middle;"></span> <span style="color:#7f8c8d;">Hors ligne</span>
                                   <?php endif; ?>
                               </td>
                       </tr>
                       <?php endforeach; ?>
                   </tbody>
               </table>
           </div>
       </div>

       <div class="card" style="margin-top:20px;">
           <h3 style="margin-bottom:15px;">🔑 Demandes de Réinitialisation de Mot de Passe</h3>
           <?php 
           $demandes = $db->query("SELECT * FROM demandes_mdp WHERE statut = 'En attente' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
           if (empty($demandes)): ?>
               <p style="margin-top:15px; color:#7f8c8d;">Aucune demande de réinitialisation en attente.</p>
           <?php else: ?>
               <div class="table-responsive">
                   <table>
                       <thead>
                           <tr><th>Nom de l'utilisateur</th><th>Nouveau mot de passe souhaité</th><th>Action</th></tr>
                       </thead>
                       <tbody>
                           <?php foreach ($demandes as $dem): ?>
                               <tr>
                                   <td><strong><?= htmlspecialchars($dem['nom_utilisateur']) ?></strong></td>
                                   <td><code><?= htmlspecialchars($dem['nouveau_mdp']) ?></code></td>
                                   <td>
                                       <form method="POST" style="display:inline;">
                                           <input type="hidden" name="action" value="valider_demande_mdp">
                                           <input type="hidden" name="id_demande" value="<?= $dem['id'] ?>">
                                           <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Valider</button>
                                       </form>
                                       <form method="POST" style="display:inline; margin-left:5px;">
                                           <input type="hidden" name="action" value="rejeter_demande_mdp">
                                           <input type="hidden" name="id_demande" value="<?= $dem['id'] ?>">
                                           <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-x-circle"></i> Rejeter</button>
                                       </form>
                                   </td>
                               </tr>
                           <?php endforeach; ?>
                       </tbody>
                   </table>
               </div>
           <?php endif; ?>
       </div>

       <div class="card" style="margin-top:20px;">
           <h3 style="margin-bottom:15px;">🛡️ Créer un compte sécurisé (Généré par la base de données)</h3>
           <div class="table-responsive">
               <form method="POST">
                   <input type="hidden" name="action" value="creer_compte_securise">
                   <div class="form-group">
                       <label>Nom de l'utilisateur :</label>
                       <input type="text" name="nom_utilisateur" required placeholder="Ex: comptable_lspca">
                   </div>
                   <div class="form-group">
                       <label>Mot de passe :</label>
                       <input type="password" name="mot_de_passe" required placeholder="Saisissez un mot de passe sécurisé">
                   </div>
                   <div class="form-group">
                       <label>Rôle assigné :</label>
                       <select name="role_compte" required>
                           <option value="directeur">Directeur / Admin</option>
                           <option value="comptable">Comptable</option>
                           <option value="secretariat">Secrétariat</option>
                           <option value="enseignant">Enseignant</option>
                       </select>
                   </div>
                   <button type="submit" class="btn btn-primary"><i class="bi bi-shield-lock-fill"></i> Générer le compte sécurisé</button>
                   <a href="?page=home" class="btn btn-danger" style="margin-left:10px;"><i class="bi bi-arrow-left"></i> Retour</a>
               </form>
           </div>
       </div>
   <?php endif; ?>

   <!-- NOUVELLE VUE: GESTION DE L'INVENTAIRE ET DES DÉPENSES (COMPTABLE & DIRECTEUR) -->
   <?php if ($page === 'lister_inventaire' && ($role === 'directeur' || $role === 'comptable')): ?>
       <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
           <h2>📦 Gestion de l'Inventaire & Dépenses scolaires</h2>
           <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
       </div>

       <div class="card">
           <h3 style="margin-bottom:15px;">➕ Enregistrer un achat d'inventaire / Matériel</h3>
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_depense">
               <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; flex-wrap:wrap;">
                   <div class="form-group">
                       <label>Désignation de l'article :</label>
                       <input type="text" name="designation" placeholder="Ex: Achat de craies blanches (boite), Éponges..." required>
                   </div>
                   <div class="form-group">
                       <label>Catégorie de matériel :</label>
                       <select name="categorie" required>
                           <option value="Matériel Scolaire">Fournitures Scolaires (Craies, Éponges, stylos...)</option>
                           <option value="Entretien & Nettoyage">Matériel d'entretien & Nettoyage</option>
                           <option value="Matériel Administratif">Matériel Administratif / Bureautique</option>
                           <option value="Autres dépenses">Autres fournitures / Dépenses diverses</option>
                       </select>
                   </div>
               </div>
               <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:15px; flex-wrap:wrap;">
                   <div class="form-group">
                       <label>Quantité :</label>
                       <input type="number" name="quantite" min="1" value="1" required>
                   </div>
                   <div class="form-group">
                       <label>Prix Unitaire (Ar) :</label>
                       <input type="number" step="0.01" name="prix_unitaire" placeholder="Ex: 5000" required>
                   </div>
                   <div class="form-group">
                       <label>Date de dépense :</label>
                       <input type="date" name="date_depense" value="<?= date('Y-m-d') ?>" required>
                   </div>
               </div>
               <button type="submit" class="btn btn-success"><i class="bi bi-plus-circle"></i> Enregistrer l'achat</button>
           </form>
       </div>

       <div class="card">
           <h3 style="margin-bottom:15px;">📦 Registre des Matériels & Dépenses d'Inventaire</h3>
           <div class="table-responsive">
               <table>
                   <thead>
                       <tr>
                           <th>Référence</th>
                           <th>Désignation</th>
                           <th>Catégorie</th>
                           <th class="text-center">Quantité</th>
                           <th class="text-right">Prix Unitaire</th>
                           <th class="text-right">Total Payé</th>
                           <th>Date</th>
                           <th>Acheteur</th>
                           <th class="text-center">Pièce</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php 
                       $depenses = $db->query("SELECT * FROM depenses ORDER BY date_depense DESC, id DESC")->fetchAll(PDO::FETCH_ASSOC);
                       if ($depenses): foreach ($depenses as $dep): ?>
                           <tr>
                               <td><strong>#DEP-00<?= $dep['id'] ?></strong></td>
                               <td><?= htmlspecialchars($dep['designation']) ?></td>
                               <td><?= htmlspecialchars($dep['categorie']) ?></td>
                               <td class="text-center"><?= $dep['quantite'] ?></td>
                               <td class="text-right"><?= number_format($dep['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                               <td class="text-right" style="font-weight:bold; color:#e74c3c;"><?= number_format($dep['montant_total'], 2, ',', ' ') ?> Ar</td>
                               <td><?= date('d/m/Y', strtotime($dep['date_depense'])) ?></td>
                               <td><?= htmlspecialchars($dep['enregistre_par']) ?></td>
                               <td class="text-center">
                                   <a href="?print=recu_depense&depense_id=<?= $dep['id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-receipt"></i> Bon de Caisse</a>
                               </td>
                           </tr>
                       <?php endforeach; else: ?>
                           <tr><td colspan="9" class="text-center" style="color:#7f8c8d;">Aucun enregistrement de matériel dans l'inventaire.</td></tr>
                       <?php endif; ?>
                   </tbody>
               </table>
           </div>
       </div>

   <!-- NOUVELLE VUE: HISTORIQUE GÉNÉRAL DES TRANSACTIONS FINANCIÈRES (COMPTABLE & DIRECTEUR) -->
   <?php elseif ($page === 'historique_financier' && ($role === 'directeur' || $role === 'comptable')): ?>
       <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
           <h2>📊 Journal des Transactions Financières de l'Établissement</h2>
           <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
       </div>

       <?php
       $inflows = $db->query("
           SELECT p.id, 'Entrée (Recette)' as type, CONCAT('Scolarité : ', e.nom, ' (', e.matricule, ')') as details, p.montant_paye as montant, p.date_paiement as date, 'scolarite' as ref, p.id as ref_id
           FROM paiements p
           JOIN frais_scolarite fs ON p.frais_id = fs.id
           JOIN eleves e ON fs.eleve_id = e.id
       ")->fetchAll(PDO::FETCH_ASSOC);

       $outflows = $db->query("
           SELECT d.id, 'Sortie (Dépense)' as type, CONCAT('Achat : ', d.designation, ' (x', d.quantite, ')') as details, d.montant_total as montant, d.date_depense as date, 'inventaire' as ref, d.id as ref_id
           FROM depenses d
       ")->fetchAll(PDO::FETCH_ASSOC);

       $ledger = array_merge($inflows, $outflows);
       usort($ledger, function($a, $b) {
           return strtotime($b['date']) <=> strtotime($a['date']);
       });

       $total_recettes = 0;
       $total_depenses = 0;
       foreach ($ledger as $tx) {
           if ($tx['type'] === 'Entrée (Recette)') {
               $total_recettes += (float)$tx['montant'];
           } else {
               $total_depenses += (float)$tx['montant'];
           }
       }
       $solde_caisse = $total_recettes - $total_depenses;
       ?>

       <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:15px; margin-bottom:20px;">
           <div class="card" style="border-left: 5px solid #27ae60;">
               <span style="font-size:12px; font-weight:600; text-transform:uppercase; color:#7f8c8d;">Total des Recettes (Scolarités)</span>
               <h3 style="font-size:24px; color:#27ae60; margin-top:5px;">+ <?= number_format($total_recettes, 2, ',', ' ') ?> Ar</h3>
           </div>
           <div class="card" style="border-left: 5px solid #e74c3c;">
               <span style="font-size:12px; font-weight:600; text-transform:uppercase; color:#7f8c8d;">Total des Dépenses (Inventaire)</span>
               <h3 style="font-size:24px; color:#e74c3c; margin-top:5px;">- <?= number_format($total_depenses, 2, ',', ' ') ?> Ar</h3>
           </div>
           <div class="card" style="border-left: 5px solid #f1c40f;">
               <span style="font-size:12px; font-weight:600; text-transform:uppercase; color:#7f8c8d;">Solde de Caisse Actuel</span>
               <h3 style="font-size:24px; color:#1a252f; margin-top:5px; font-weight:bold;"><?= number_format($solde_caisse, 2, ',', ' ') ?> Ar</h3>
           </div>
       </div>

       <div class="card">
           <h3 style="margin-bottom:15px;">📖 Livre de Caisse de l'Établissement</h3>
           <div class="table-responsive">
               <table>
                   <thead>
                       <tr>
                           <th>Date</th>
                           <th>Catégorie</th>
                           <th>Description / Détails de l'opération</th>
                           <th class="text-right">Entrées (+)</th>
                           <th class="text-right">Sorties (-)</th>
                           <th class="text-center">Justificatif</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php if ($ledger): foreach ($ledger as $tx): ?>
                           <tr>
                               <td><?= date('d/m/Y', strtotime($tx['date'])) ?></td>
                               <td>
                                   <?php if ($tx['type'] === 'Entrée (Recette)'): ?>
                                       <span class="badge" style="background-color:#27ae60; color:white;">Recette</span>
                                   <?php else: ?>
                                       <span class="badge" style="background-color:#e74c3c; color:white;">Dépense</span>
                                   <?php endif; ?>
                               </td>
                               <td><?= htmlspecialchars($tx['details']) ?></td>
                               <td class="text-right" style="font-weight:bold; color:#27ae60;">
                                   <?= $tx['type'] === 'Entrée (Recette)' ? '+' . number_format($tx['montant'], 2, ',', ' ') . ' Ar' : '-' ?>
                               </td>
                               <td class="text-right" style="font-weight:bold; color:#e74c3c;">
                                   <?= $tx['type'] === 'Sortie (Dépense)' ? '-' . number_format($tx['montant'], 2, ',', ' ') . ' Ar' : '-' ?>
                               </td>
                               <td class="text-center">
                                   <?php if ($tx['type'] === 'Entrée (Recette)'): ?>
                                       <a href="?print=recu&frais_id=<?= $tx['ref_id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-receipt"></i> Reçu Scolarité</a>
                                   <?php else: ?>
                                       <a href="?print=recu_depense&depense_id=<?= $tx['ref_id'] ?>" class="btn btn-orange btn-sm"><i class="bi bi-file-earmark-text"></i> Bon de Caisse</a>
                                   <?php endif; ?>
                               </td>
                           </tr>
                       <?php endforeach; else: ?>
                           <tr><td colspan="6" class="text-center" style="color:#7f8c8d;">Aucun mouvement financier enregistré.</td></tr>
                       <?php endif; ?>
                   </tbody>
               </table>
           </div>
       </div>
   <?php endif; ?>

   <!-- SÉCURISATION : VUE COMPATIBLE AVEC TOUTES LES AUTRES PAGES (ÉCRITURE ENTIÈRE) -->
   <?php if ($page === 'ajouter_absence' && ($role === 'secretariat' || $role === 'directeur' || $role === 'enseignant')): ?>
       <h2>📅 Enregistrer une absence / retard</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_absence">
               <div class="form-group">
                   <label>Élève :</label>
                   <select name="eleve_id" required>
                       <option value="">-- Choisir l'élève --</option>
                       <?php foreach ($eleves as $e): ?>
                           <option value="<?= $e['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $e['id']) ? 'selected' : '' ?>><?= htmlspecialchars($e['matricule']) ?> - <?= htmlspecialchars($e['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Date de l'incident :</label><input type="date" name="date" required value="<?= date('Y-m-d') ?>"></div>
               <div class="form-group">
                   <label>Type :</label>
                   <select name="type_absence" required><option value="Absence">Absence</option><option value="Retard">Retard</option></select>
               </div>
               <div class="form-group">
                   <label>Justifié :</label>
                   <select name="justifie" required><option value="1">Oui</option><option value="0">Non</option></select>
               </div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Enregistrer</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <!-- OPTION ABSENCES AVEC FILTRE INTÉGRÉ PAR CLASSE/NIVEAU/ÉLÈVE -->
   <?php elseif ($page === 'lister_absences' && $role !== 'comptable'): ?>
       <h2>📅 Suivi d'Assiduité des Élèves (Filtres Avancés)</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <div style="margin-bottom: 15px;">
               <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </div>

           <h3 style="margin-top:15px; margin-bottom:15px;"><i class="bi bi-list-check"></i> État de répartition de l'assiduité</h3>
           <?php if (!empty($absences_eleve)): ?>
           <div class="table-responsive">
               <table>
                   <thead><tr><th>Élève</th><th>Matricule</th><th>Date de l'incident</th><th>Type</th><th>État justification</th></tr></thead>
                   <tbody>
                   <?php foreach ($absences_eleve as $a): ?>
                       <tr>
                           <td><strong><?= htmlspecialchars($a['nom'] ?? 'Inconnu') ?></strong></td>
                           <td><?= htmlspecialchars($a['matricule'] ?? 'N/A') ?></td>
                           <td><?= date('d/m/Y', strtotime($a['date'])) ?></td>
                           <td>
                               <?php if ($a['type'] === 'Absence'): ?>
                                   <span class="badge" style="background-color:#e74c3c; color:white;">Absence</span>
                               <?php else: ?>
                                   <span class="badge" style="background-color:#e67e22; color:white;">Retard</span>
                               <?php endif; ?>
                           </td>
                           <td><?= $a['justifie'] ? '<span style="color:#2ece73; font-weight:bold;"><i class="bi bi-patch-check-fill"></i> Justifié</span>' : '<span style="color:#e74c3c; font-weight:bold;"><i class="bi bi-patch-exclamation-fill"></i> Non-justifié</span>' ?></td>
                       </tr>
                   <?php endforeach; ?>
                   </tbody>
               </table>
           </div>
           <?php else: ?>
               <p style="color:#95a5a6; padding:10px;">Aucun enregistrement d'absence ou de retard ne correspond à ce filtre.</p>
           <?php endif; ?>
       </div>

   <?php elseif ($page === 'ajouter_sanction' && ($role === 'directeur' || $role === 'secretariat' || $role === 'enseignant')): ?>
       <h2>⚠️ Enregistrer une sanction disciplinaire</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_sanction">
               <div class="form-group">
                   <label>Élève puni :</label>
                   <select name="eleve_id" required>
                       <option value="">-- Choisir l'élève --</option>
                       <?php foreach ($eleves as $e): ?>
                           <option value="<?= $e['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $e['id']) ? 'selected' : '' ?>><?= htmlspecialchars($e['matricule']) ?> - <?= htmlspecialchars($e['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Date de la punition :</label><input type="date" name="date" required value="<?= date('Y-m-d') ?>"></div>
               <div class="form-group"><label>Type de sanction :</label><input type="text" name="type_sanction" placeholder="Ex: Avertissement, Blâme, Heure de colle..." required></div>
               <div class="form-group"><label>Motif de la sanction :</label><input type="text" name="motif" required></div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-exclamation-triangle"></i> Enregistrer la sanction</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <!-- OPTION SANCTIONS AVEC FILTRE INTÉGRÉ PAR CLASSE/NIVEAU/ÉLÈVE -->
   <?php elseif ($page === 'lister_sanctions' && $role !== 'comptable'): ?>
       <h2>📋 Registre des Sanctions Disciplinaires (Filtres Avancés)</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <div style="margin-bottom: 15px;">
               <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </div>

           <h3 style="margin-top:15px; margin-bottom:15px;"><i class="bi bi-shield-fill-exclamation"></i> État de répartition des punitions</h3>
           <?php if (!empty($sanctions_eleve)): ?>
           <div class="table-responsive">
               <table>
                   <thead><tr><th>Élève</th><th>Matricule</th><th>Date</th><th>Type Sanction</th><th>Motif détaillé de la sanction</th></tr></thead>
                   <tbody>
                   <?php foreach ($sanctions_eleve as $s): ?>
                       <tr>
                           <td><strong><?= htmlspecialchars($s['nom'] ?? 'Inconnu') ?></strong></td>
                           <td><?= htmlspecialchars($s['matricule'] ?? 'N/A') ?></td>
                           <td><?= date('d/m/Y', strtotime($s['date'])) ?></td>
                           <td><span style="font-weight:bold; color:#e67e22;"><?= htmlspecialchars($s['type']) ?></span></td>
                           <td><?= htmlspecialchars($s['motif']) ?></td>
                       </tr>
                   <?php endforeach; ?>
                   </tbody>
               </table>
           </div>
           <?php else: ?>
               <p style="color:#95a5a6; padding:10px;">Aucun enregistrement disciplinaire ne correspond à cette sélection.</p>
           <?php endif; ?>
       </div>

   <?php elseif ($page === 'ajouter_note' && $role === 'enseignant'): ?>
       <h2>📝 Enregistrer une Note d'Examen</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_note">
               <div class="form-group">
                   <label>Élève :</label>
                   <select name="eleve_id" required>
                       <option value="">-- Choisir --</option>
                       <?php foreach ($eleves as $e): ?>
                           <option value="<?= $e['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $e['id']) ? 'selected' : '' ?>><?= htmlspecialchars($e['matricule']) ?> - <?= htmlspecialchars($e['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group">
                   <label>Examen (1 à 6) :</label>
                   <select name="examen_num" required>
                       <?php for($i=1;$i<=6;$i++): ?><option value="<?= $i ?>">Examen <?= $i ?></option><?php endfor; ?>
                   </select>
               </div>
               <div class="form-group"><label>Matière :</label><input type="text" name="matiere" required></div>
               <div class="form-group"><label>Note brute sur 20 :</label><input type="number" name="note" step="0.1" min="0" max="20" required></div>
               <div class="form-group"><label>Coefficient :</label><input type="number" name="coefficient" min="1" max="10" value="1" required></div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-check2-square"></i> Enregistrer Note</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'modifier_note' && ($role === 'enseignant' || $role === 'directeur')): ?>
       <h2>✏️ Modifier une Note d'Examen</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="modifier_note">
               <div class="form-group">
                   <label>Sélectionner la note à modifier :</label>
                   <select name="note_id" required>
                       <option value="">-- Choisir la note --</option>
                       <?php 
                       $notes_all = $db->query("SELECT n.id, n.matiere, n.note, n.coefficient, n.eleve_id, n.examen_num, e.nom FROM notes n JOIN eleves e ON n.eleve_id = e.id ORDER BY e.nom ASC, n.examen_num ASC")->fetchAll(PDO::FETCH_ASSOC);
                       foreach ($notes_all as $nt): ?>
                           <option value="<?= $nt['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === (int)$nt['eleve_id']) ? 'selected' : '' ?>><?= htmlspecialchars($nt['nom']) ?> - Examen <?= $nt['examen_num'] ?> - <?= htmlspecialchars($nt['matiere']) ?> (<?= $nt['note'] ?>/20, Coeff: <?= $nt['coefficient'] ?>)</option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group">
                   <label>Nouvelle Note brute sur 20 :</label>
                   <input type="number" name="note" step="0.1" min="0" max="20" required>
               </div>
               <div class="form-group">
                   <label>Nouveau Coefficient :</label>
                   <input type="number" name="coefficient" min="1" max="10" required>
               </div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Enregistrer les modifications</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'moyenne_eleve'): ?>
       <h2>📊 Moyenne des élèves</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <div style="margin-bottom: 15px;">
               <a href="?page=home" class="btn btn-danger btn-sm"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </div>
           
           <?php if ($moyenne_result !== null): ?>
               <div class="alert success" style="margin-top:16px;">Moyenne de l'élève sélectionné : <strong><?= htmlspecialchars($moyenne_result) ?> / 20</strong></div>
           <?php endif; ?>

           <?php if (!empty($moyennes_liste)): ?>
               <h3 style="margin-top:20px; margin-bottom:15px;"><i class="bi bi-calculator"></i> Récapitulatif académique du groupe</h3>
               <div class="table-responsive">
                   <table>
                       <thead>
                           <tr><th>Matricule</th><th>Nom de l'élève</th><?php if(isset($moyennes_liste[0]['classe'])): ?><th>Classe</th><?php endif; ?><th>Moyenne académique</th></tr>
                       </thead>
                       <tbody>
                           <?php foreach ($moyennes_liste as $ml): ?>
                               <tr <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === (int)$ml['matricule']) ? 'style="background-color:#1e3d2f;"' : '' ?>>
                                   <td><strong><?= htmlspecialchars($ml['matricule']) ?></strong></td>
                                   <td><?= htmlspecialchars($ml['nom']) ?></td>
                                   <?php if(isset($ml['classe'])): ?><td><?= htmlspecialchars($ml['classe']) ?></td><?php endif; ?>
                                   <td>
                                       <?php if ($ml['moyenne'] !== 'Aucune note'): ?>
                                           <strong style="color: <?= (float)$ml['moyenne'] >= 10 ? '#2ece73' : '#e74c3c' ?>;"><?= htmlspecialchars($ml['moyenne']) ?> / 20</strong>
                                       <?php else: ?>
                                           <span style="color:#95a5a6; font-style:italic;">Aucune note</span>
                                       <?php endif; ?>
                                   </td>
                               </tr>
                           <?php endforeach; ?>
                       </tbody>
                   </table>
               </div>
           <?php endif; ?>
       </div>

   <?php elseif ($page === 'ajouter_eleve' && $role === 'secretariat'): ?>
       <h2>➕ Inscrire un nouvel élève</h2>
       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="ajouter_eleve">
               <div class="form-group"><label>Matricule :</label><input type="text" name="matricule" value="ELV-<?= date('Y') ?>-<?= rand(1000, 9999) ?>" required></div>
               <div class="form-group"><label>Nom complet :</label><input type="text" name="nom_eleve" required></div>
               <div class="form-group"><label>Âge :</label><input type="number" name="age" required min="3" max="25"></div>
               <div class="form-group">
                   <label>Classe :</label>
                   <select name="classe_id" required>
                       <option value="">-- Choisir la classe --</option>
                       <?php foreach ($classes_data as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom_classe']) ?></option><?php endforeach; ?>
                   </select>
               </div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Inscrire l'élève</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'modifier_eleve' && $role === 'secretariat'): ?>
       <h2>✏️ Modifier les informations d'un élève</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST">
               <input type="hidden" name="action" value="modifier_eleve">
               <div class="form-group">
                   <label>Sélectionner l'élève :</label>
                   <select name="eleve_id" required>
                       <option value="">-- Choisir --</option>
                       <?php foreach ($eleves as $e): ?>
                           <option value="<?= $e['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $e['id']) ? 'selected' : '' ?>><?= htmlspecialchars($e['matricule']) ?> - <?= htmlspecialchars($e['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group"><label>Nouveau Matricule :</label><input type="text" name="matricule" required></div>
               <div class="form-group"><label>Nom complet :</label><input type="text" name="nom_eleve" required></div>
               <div class="form-group"><label>Âge :</label><input type="number" name="age" required min="3" max="25"></div>
               <div class="form-group">
                   <label>Classe :</label>
                   <select name="classe_id" required>
                       <?php foreach ($classes_data as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom_classe']) ?></option><?php endforeach; ?>
                   </select>
               </div>
               <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Enregistrer modifications</button>
               <a href="?page=home" class="btn btn-danger" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Retour</a>
           </form>
       </div>

   <?php elseif ($page === 'supprimer_eleve' && $role === 'secretariat'): ?>
       <h2>🗑️ Supprimer un élève</h2>
       
       <!-- Filtre élève unifié -->
       <?= $html_filtre_eleve ?>

       <div class="card">
           <form method="POST" onsubmit="return confirm('Confirmer la radiation définitive de cet élève ?')">
               <input type="hidden" name="action" value="supprimer_eleve">
               <div class="form-group">
                   <label>Élève à radier :</label>
                   <select name="eleve_id" required>
                       <option value="">-- Choisir l'élève --</option>
                       <?php foreach ($eleves as $e): ?>
                           <option value="<?= $e['id'] ?>" <?= (isset($_GET['eleve_id']) && (int)$_GET['eleve_id'] === $e['id']) ? 'selected' : '' ?>><?= htmlspecialchars($e['matricule']) ?> - <?= htmlspecialchars($e['nom']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Radier l'élève</button>
               <a href="?page=home" class="btn btn-primary" style="margin-left: 10px;"><i class="bi bi-arrow-left-circle"></i> Annuler / Retour</a>
           </form>
       </div>

   <?php else: ?>
      
   <?php endif; ?>

<?php endif; ?>

<script src="script.js"></script>

</body>
</html>