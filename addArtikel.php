<?php
include("sqlcon.php");

$conn = dbconn();

session_start();

if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: login.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$act = isset($_GET['a']) ? $_GET['a'] : "";

if ($act == "i") {
    if (isset($_POST['judul']) && isset($_POST['subjudul']) && isset($_POST['content']) && isset($_POST['image_url']) && isset($_POST['tanggal']) && isset($_POST['penulis'])) {
        $judul = $_POST['judul'];
        $subjudul = $_POST['subjudul'];
        $content = $_POST['content'];
        $image_url = $_POST['image_url'];
        $tanggal = $_POST['tanggal'];
        $penulis = $_POST['penulis'];

        $sql = "INSERT INTO artikel (judul, subjudul, content, image_url, tanggal, penulis) VALUES ('$judul', '$subjudul', '$content', '$image_url', '$tanggal', '$penulis')";
        $result = mysqli_query($conn, $sql);
    } else {
        echo "One or more fields are not set in the POST request.";
    }
}

if ($act == "d") {
    if (isset($_GET['id'])) {
        $id_to_delete = $_GET['id'];
        $sql = "DELETE FROM artikel WHERE id = '$id_to_delete'";
        $result = mysqli_query($conn, $sql);
    } else {
        echo "id is not set in the URL.";
    }
}

if ($act == "e") {
    if (isset($_GET['id'])) {
        $id_to_edit = $_GET['id'];
        $sql = "SELECT * FROM artikel WHERE id = '$id_to_edit'";
        $result = mysqli_query($conn, $sql);
        $student = mysqli_fetch_assoc($result);

        if ($student) {
            if (isset($_POST['judul']) && isset($_POST['subjudul']) && isset($_POST['content']) && isset($_POST['image_url']) && isset($_POST['tanggal']) && isset($_POST['penulis'])) {
                $new_judul = $_POST['judul'];
                $new_subjudul = $_POST['subjudul'];
                $new_content = $_POST['content'];
                $new_image_url = $_POST['image_url'];
                $new_tanggal = $_POST['tanggal'];
                $new_penulis = $_POST['penulis'];

                $update_sql = "UPDATE artikel SET judul = '$new_judul', subjudul = '$new_subjudul', content = '$new_content', image_url = '$new_image_url', tanggal = '$new_tanggal', penulis = '$new_penulis' WHERE id = '$id_to_edit'";
                $update_result = mysqli_query($conn, $update_sql);

                if ($update_result) {
                    echo "Data updated successfully.";
                } else {
                    echo "Error updating data: " . mysqli_error($conn);
                }
            }

            ?>
            <form method="post" action="?a=e&id=<?php echo $id_to_edit; ?>">
                Judul: <input name="judul" type="text" value="<?php echo $student['judul']; ?>"><br>
                Subjudul: <input name="subjudul" type="text" value="<?php echo $student['subjudul']; ?>"><br>
                Content: <input name="content" type="text" value="<?php echo $student['content']; ?>"><br>
                Image URL: <input name="image_url" type="text" value="<?php echo $student['image_url']; ?>"><br>
                Tanggal: <input name="tanggal" type="text" value="<?php echo $student['tanggal']; ?>"><br>
                Penulis: <input name="penulis" type="text" value="<?php echo $student['penulis']; ?>"><br>
                <button type="submit">Simpan Perubahan</button>
            </form>
            <?php
        } else {
            echo "Data not found for id: $id_to_edit";
        }
    } else {
        echo "id is not set in the URL for editing.";
    }
}

$sql = "SELECT * FROM artikel";
$result = mysqli_query($conn, $sql);
?>

<table style="width: 100%">
    <tr>
        <td>Judul</td>
        <td>Subjudul</td>
        <td>Content</td>
        <td>Image URL</td>
        <td>Tanggal</td>
        <td>Penulis</td>
        <td>Action</td>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['judul']; ?></td>
            <td><?php echo $row['subjudul']; ?></td>
            <td><?php echo $row['content']; ?></td>
            <td><?php echo $row['image_url']; ?></td>
            <td><?php echo $row['tanggal']; ?></td>
            <td><?php echo $row['penulis']; ?></td>
            <td>
                <a href="?a=e&id=<?php echo $row['id']; ?>">Edit Data</a>
                <a href="?a=d&id=<?php echo $row['id']; ?>">Hapus Data</a>
            </td>
        </tr>
    <?php } ?>
</table>

<form method="post" action="?a=i" onsubmit="return validateForm()">
    <table style="width: 100%">
    <tr>
            <td>Judul</td>
            <td><input name="judul" type="text"></td>
        </tr>
        <tr>
            <td>Subjudul</td>
            <td><input name="subjudul" type="text"></td>
        </tr>
        <tr>
            <td>Content</td>
            <td><input name="content" type="text"></td>
        </tr>
        <tr>
            <td>Image URL</td>
            <td><input name="image_url" type="text"></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><input name="tanggal" type="text"></td>
        </tr>
        <tr>
            <td>Penulis</td>
            <td><input name="penulis" type="text"></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit">Tambah</button></td>
        </tr>
    </table>
</form>

<script>
function validateForm() {
    var judul = document.getElementsByName("judul")[0].value;
    var subjudul = document.getElementsByName("subjudul")[0].value;
    var content = document.getElementsByName("content")[0].value;
    var image_url = document.getElementsByName("image_url")[0].value;
    var tanggal = document.getElementsByName("tanggal")[0].value;
    var penulis = document.getElementsByName("penulis")[0].value;

    if (judul === "" || subjudul === "" || content === "" || image_url === "" || tanggal === "" || penulis === "") {
        alert("Please fill in all fields");
        return false;
    }

    return true;
}
</script>

<?php
mysqli_close($conn);
?>
