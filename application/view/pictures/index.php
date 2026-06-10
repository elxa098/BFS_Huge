<div class="container">
    <h1>Gallery</h1>

    <style>
        .panel {
            width: 100%;
        }

        .panel.gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .panel.gallery img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            border-radius: 6px;
        }
    </style>

    <!-- Upload -->
    <div class="panel">
        <form method="post"
              enctype="multipart/form-data"
              action="<?= Config::get('URL'); ?>pictures/upload">
            <input type="file" name="datei" accept=".jpg,.png,.pdf">
            <button type="submit">Hochladen</button>
        </form>
    </div> 

    <!-- Display Gallery -->
    <h2>Your Pictures</h2>
    <div class="panel gallery">
        <?php if (!empty($this->data['pictures'])) : ?>
            <?php foreach ($this->data['pictures'] as $picture) : ?>

                <img src="<?= Config::get('URL'); ?>pictures/image/<?= $picture->id ?>">

            <?php endforeach; ?>
        <?php else : ?>
            <p>No pictures uploaded yet.</p>
        <?php endif; ?>
    </div>
</div>