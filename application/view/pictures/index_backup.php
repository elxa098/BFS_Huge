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

                <div style="position: relative;">
                    <img src="<?= Config::get('URL'); ?>pictures/image/<?= $picture->id ?>">
                    <div style="background: rgba(0,0,0,0.8); color: white; padding: 8px; font-size: 12px; border-radius: 0 0 6px 6px;">
                        <small>Share link:</small><br>
                        <input type="text" value="<?= Config::get('URL'); ?>pictures/image/<?= $picture->link ?>" 
                               style="width: 100%; padding: 4px; border: none; border-radius: 3px; font-size: 11px; margin-bottom: 8px;" 
                               readonly onclick="this.select()">
                        <div style="display: flex; gap: 6px; margin-top: 8px;">
                            <a href="<?= Config::get('URL'); ?>pictures/download/<?= $picture->id ?>" 
                               style="flex: 1; display: inline-block; text-align: center; background: #007bff; color: white; padding: 6px 0; border-radius: 3px; text-decoration: none; font-size: 11px;">Download</a>
                            <form method="post" action="<?= Config::get('URL'); ?>pictures/delete/<?= $picture->id ?>" style="flex: 1; margin: 0;">
                                <button type="submit" style="width: 100%; padding: 6px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 11px;" onclick="return confirm('Are you sure you want to delete this picture?');">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else : ?>
            <p>No pictures uploaded yet.</p>
        <?php endif; ?>
    </div>
</div>