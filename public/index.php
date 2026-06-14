<?php require_once __DIR__ . '/../includes/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1>Discover Your Next Adventure</h1>
        <p>Explore amazing destinations with AI-powered tour customization</p>
        <div class="search-bar">
            <input type="text" id="search-destination" placeholder="Search destination..." class="search-input">
            <button class="search-btn"><i class="fas fa-search"></i> Search</button>
        </div>
    </div>
</section>

<section class="featured-packages">
    <div class="container">
        <h2>Featured Packages</h2>
        <div class="packages-grid">
            <?php
            $result = $conn->query("SELECT * FROM packages LIMIT 6");
            if ($result->num_rows > 0) {
                while ($package = $result->fetch_assoc()) {
                    ?>
                    <div class="package-card">
                        <img src="<?php echo htmlspecialchars($package['image_url']); ?>" alt="<?php echo htmlspecialchars($package['name']); ?>">
                        <div class="package-info">
                            <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($package['description'], 0, 100)) . '...'; ?></p>
                            <div class="package-meta">
                                <span class="duration"><i class="fas fa-calendar"></i> <?php echo $package['duration']; ?> days</span>
                                <span class="price"><?php echo formatCurrency($package['price']); ?></span>
                            </div>
                            <a href="pages/package-detail.php?id=<?php echo $package['id']; ?>" class="btn-primary">View Details</a>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>

<section class="ai-customize">
    <div class="container">
        <h2>Customize Your Tour with AI</h2>
        <p>Let our AI assistant help you design the perfect tour package within your budget</p>
        <a href="pages/customize-tour.php" class="btn-large">Start Customizing</a>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>