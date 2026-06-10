<div class="container">
    <h1>Gallery</h1>

    <!-- Upload -->
    <div class="box">
        <form   method="post" 
                enctype="multipart/form-data" 
                action="<?= Config::get('URL'); ?>pictures/upload"
            >
            <input type="file" name="datei" accept=".jpg,.png,.pdf">
            <button type="submit">Hochladen</button>
        </form>
    </div> 
    
    <!-- Display Gallery -->
    <h2>Your Pictures</h2>
    <div class="box">
        <?php if (!empty($this->data['pictures'])) : ?>
            <?php foreach ($this->data['pictures'] as $picture) : ?>

            <img src="<?= Config::get('URL'); ?>pictures/image/<?= $picture->id ?>">
            
            <?php endforeach; ?>
        <?php else : ?>
            <p>No pictures uploaded yet.</p>
        <?php endif; ?>
    </div>
</div>