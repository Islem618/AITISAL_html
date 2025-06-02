<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit;
}
$me = (int) ($_SESSION['user_id'] ?? $_SESSION['id_User'] ?? 0);
if ($me <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "
  SELECT
    u.id_User           AS suggestion_id,
    u.prenom            AS suggestion_prenom,
    u.nom               AS suggestion_nom,
    COALESCE(mutual.count_mutual, 0)   AS mutual_count,
    COALESCE(inter.count_interest, 0)  AS interest_count
  FROM user u

  /* ── Sous-requête pour compter les amis en commun (“friend-of-friend”) ── */
  LEFT JOIN (
      SELECT 
        f2.to_id AS fof_id,
        COUNT(*) AS count_mutual
      FROM friendship f1
      JOIN friendship f2
        ON f2.from_id = f1.to_id
      WHERE f1.from_id = ?      /* 1er paramètre = $me */
        AND f2.to_id <> ?       /* 2ème paramètre = $me */
      GROUP BY f2.to_id
  ) AS mutual
    ON mutual.fof_id = u.id_User

  /* ── Sous-requête pour compter les intérêts en commun ── */
  LEFT JOIN (
      SELECT 
        ui2.user_id      AS other_user,
        COUNT(*)         AS count_interest
      FROM user_interest ui1
      JOIN user_interest ui2
        ON ui2.interest_id = ui1.interest_id
      WHERE ui1.user_id    = ?  /* 3ème paramètre = $me */
        AND ui2.user_id    <> ? /* 4ème paramètre = $me */
      GROUP BY ui2.user_id
  ) AS inter
    ON inter.other_user = u.id_User

  /* ── On exclut les amis déjà directs de $me ── */
  LEFT JOIN friendship ex
    ON ex.from_id = ?        /* 5ème paramètre = $me */
   AND ex.to_id   = u.id_User

  /* ── On s’assure de ne pas proposer l’utilisateur à lui-même
         et d’exclure les “ex” (les amis directs déjà existants) ── */
  WHERE u.id_User <> ?      /* 6ème paramètre = $me */
    AND ex.from_id IS NULL

  ORDER BY mutual_count DESC,
           interest_count DESC,
           u.prenom ASC
  LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->execute([ $me, $me, $me, $me, $me, $me ]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows, JSON_UNESCAPED_UNICODE);