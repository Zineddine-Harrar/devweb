<?php
// Connexion à la base de données MySQL
$host = "localhost";
$user = "root";
$password = "";
$dbname = "rna";

$dsn = "mysql:host=$host;dbname=$dbname";
$pdo = new PDO($dsn, $user, $password);

// Fonction pour créer une nouvelle entrée
function create($rna_id, $rna_id_ex, $gestion) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO data (rna_id, rna_id_ex, gestion) VALUES (%s, %s, %s)");
    $stmt->execute([$rna_id, $rna_id_ex, $gestion]);
    return $pdo->lastInsertId();
}

// Fonction pour récupérer toutes les entrées
function read() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM data");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer une entrée par ID
function readById($rna_idd) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM data WHERE rna_id = ?");
    $stmt->execute([$rna_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour une entrée
function update($rna_id, $rna_id_ex, $gestion) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE data SET rna_id = ?, rna_id_ex = ?, gestion = ? WHERE rna_id = ?");
    $stmt->execute([$rna_id, $rna_id_ex, $gestion]);
}

// Fonction pour supprimer une entrée
function delete($rna_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM data WHERE rna_id = ?");
    $stmt->execute([$rna_id]);
}

// Exemples d'utilisation
// Création d'une entrée
$new_id = create("valeur_rna_id", "valeur_rna_id_ex", "valeur_gestion");
echo "Nouvelle entrée créée avec l'ID $new_id";

// Récupération de toutes les entrées
$data = read();
foreach ($data as $row) {
    echo "<p>RNA ID: ".$row['rna_id']." | RNA ID EX: ".$row['rna_id_ex']." | Gestion: ".$row['gestion']."</p>";
}

// Récupération d'une entrée par ID
$entry = readById("valeur_rna_id");
echo "<p>RNA ID: ".$entry['rna_id']." | RNA ID EX: ".$entry['rna_id_ex']." | Gestion: ".$entry['gestion']."</p>";

// Mise à jour d'une entrée
update("nouvelle_valeur_rna_id", "nouvelle_valeur_rna_id_ex", "nouvelle_valeur_gestion", "valeur_rna_id");

// Suppression d'une entrée
delete("valeur_rna_id");


// Récupération des données pour le graphique
$stmt = $pdo->query("SELECT gestion, COUNT(*) as count FROM data GROUP BY gestion");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Création d'un tableau avec les labels et les données
$labels = [];
$count_data = [];
foreach ($data as $row) {
    $labels[] = $row['gestion'];
    $count_data[] = $row['count'];
}

// Création du graphique
echo '<canvas id="myChart"></canvas>';
echo '<script>';
echo 'var ctx = document.getElementById("myChart").getContext("2d");';
echo 'var myChart = new Chart(ctx, {';
echo '    type: "bar",';
echo '    data: {';
echo '        labels: ' . json_encode($labels) . ',';
echo '        datasets: [{';
echo '            label: "Nombre d\'entrées",';
echo '            data: ' . json_encode($count_data) . ',';
echo '            backgroundColor: [';
echo '                "rgba(255, 99, 132, 0.2)",';
echo '                "rgba(54, 162, 235, 0.2)",';
echo '                "rgba(255, 206, 86, 0.2)",';
echo '                "rgba(75, 192, 192, 0.2)",';
echo '                "rgba(153, 102, 255, 0.2)",';
echo '                "rgba(255, 159, 64, 0.2)"';
echo '            ],';
echo '            borderColor: [';
echo '                "rgba(255, 99, 132, 1)",';
echo '                "rgba(54, 162, 235, 1)",';
echo '                "rgba(255, 206, 86, 1)",';
echo '                "rgba(75, 192, 192, 1)",';
echo '                "rgba(153, 102, 255, 1)",';
echo '                "rgba(255, 159, 64, 1)"';
echo '            ],';
echo '            borderWidth: 1';
echo '        }]';
echo '    },';
echo '    options: {';
echo '        scales: {';
echo '            yAxes: [{';
echo '                ticks: {';
echo '                    beginAtZero: true';
echo '                }';
echo '            }]';
echo '        }';
echo '    }';
echo '});';
echo '</script>';
?>

