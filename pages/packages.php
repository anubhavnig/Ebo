<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="packages-container">
    <h1>Explore Our Tour Packages</h1>
    
    <div class="packages-filter">
        <input type="text" id="searchInput" placeholder="Search packages..." class="filter-input">
        <select id="budgetFilter" class="filter-input">
            <option value="">All Budgets</option>
            <option value="0-15000">₹0 - ₹15,000</option>
            <option value="15000-25000">₹15,000 - ₹25,000</option>
            <option value="25000-50000">₹25,000 - ₹50,000</option>
            <option value="50000+">₹50,000+</option>
        </select>
    </div>
    
    <div id="packagesGrid" class="packages-grid">
        <!-- Packages will load here -->
    </div>
</div>

<style>
.packages-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.packages-container h1 {
    text-align: center;
    margin-bottom: 2rem;
    color: var(--dark);
}

.packages-filter {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.filter-input {
    padding: 0.75rem 1rem;
    border: 2px solid #ecf0f1;
    border-radius: 20px;
    font-family: inherit;
    font-size: 1rem;
    flex: 1;
    min-width: 200px;
}

.filter-input:focus {
    outline: none;
    border-color: var(--primary);
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.package-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s;
}

.package-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.package-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.package-info {
    padding: 1.5rem;
}

.package-info h3 {
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.package-info p {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.package-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.package-meta span {
    font-size: 0.9rem;
}

.package-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPackages();
    
    document.getElementById('searchInput').addEventListener('input', loadPackages);
    document.getElementById('budgetFilter').addEventListener('change', loadPackages);
});

async function loadPackages() {
    const search = document.getElementById('searchInput').value;
    const budget = document.getElementById('budgetFilter').value;
    
    let budgetMin = 0, budgetMax = 999999;
    if (budget === '0-15000') { budgetMin = 0; budgetMax = 15000; }
    if (budget === '15000-25000') { budgetMin = 15000; budgetMax = 25000; }
    if (budget === '25000-50000') { budgetMin = 25000; budgetMax = 50000; }
    if (budget === '50000+') { budgetMin = 50000; budgetMax = 999999; }
    
    try {
        const response = await fetch(`<?php echo SITE_URL; ?>/api/search-packages.php?search=${encodeURIComponent(search)}&budget_min=${budgetMin}&budget_max=${budgetMax}`);
        const data = await response.json();
        
        const grid = document.getElementById('packagesGrid');
        grid.innerHTML = '';
        
        if (data.packages.length === 0) {
            grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 2rem;">No packages found</p>';
            return;
        }
        
        data.packages.forEach(pkg => {
            const card = document.createElement('div');
            card.className = 'package-card';
            card.innerHTML = `
                <div class="package-image">🏖️</div>
                <div class="package-info">
                    <h3>${pkg.name}</h3>
                    <p>${pkg.description.substring(0, 80)}...</p>
                    <div class="package-meta">
                        <span>⏱️ ${pkg.duration} days</span>
                        <span class="package-price">₹${parseFloat(pkg.price).toLocaleString()}</span>
                    </div>
                    <a href="package-detail.php?id=${pkg.id}" class="btn-primary" style="width: 100%; text-align: center; display: block;">View Details</a>
                </div>
            `;
            grid.appendChild(card);
        });
    } catch (error) {
        console.error('Error loading packages:', error);
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
