<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

<div class="container">
    <h1>Agregar Menú</h1>
    <form action="procesar_menu.php" method="post">
        <div class="form-group">
            <label for="nombre">Nombre del menú:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>