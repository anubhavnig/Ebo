<?php require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}
?>

<div class="customize-container">
    <h1>Customize Your Tour with AI ✈️</h1>
    <p class="subtitle">Tell us your preferences and let Gemini AI create the perfect tour package for you</p>
    
    <div class="customize-content">
        <div class="customize-form-section">
            <h2>Your Preferences</h2>
            <form id="customizeForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Destination *</label>
                        <input type="text" id="destination" placeholder="e.g., Goa, Kerala, Himachal" required>
                        <small>Popular: Goa, Kerala, Himachal, Rajasthan, Assam, Tamil Nadu</small>
                    </div>
                    <div class="form-group">
                        <label>Duration (Days) *</label>
                        <input type="number" id="duration" min="1" max="30" placeholder="e.g., 5" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Budget (₹) *</label>
                        <input type="number" id="budget" min="1000" placeholder="e.g., 50000" required>
                        <small>Total budget for all travelers</small>
                    </div>
                    <div class="form-group">
                        <label>Number of Travelers *</label>
                        <input type="number" id="travelers" min="1" max="10" value="1" required>
                    </div>
                </div>
                
                <div class="form-group full">
                    <label>Preferences & Interests</label>
                    <textarea id="preferences" placeholder="e.g., beach lover, adventure seeker, cultural enthusiast, budget-conscious, luxury experience" rows="4"></textarea>
                </div>
                
                <button type="submit" class="btn-large">Generate Custom Tour Plan</button>
            </form>
        </div>
        
        <div class="customize-result-section" id="resultSection" style="display: none;">
            <h2>Your Custom Tour Plan</h2>
            <div id="aiResponse" class="ai-response"></div>
            <button class="btn-primary" onclick="bookCustomTour()">Proceed to Booking</button>
            <button class="btn-secondary" onclick="resetForm()">Create Another Plan</button>
        </div>
    </div>
</div>

<style>
.customize-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 3rem 2rem;
}

.customize-container h1 {
    text-align: center;
    color: var(--dark);
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.subtitle {
    text-align: center;
    color: #7f8c8d;
    font-size: 1.1rem;
    margin-bottom: 3rem;
}

.customize-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.customize-form-section,
.customize-result-section {
    background: var(--white);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--dark);
}

.form-group input,
.form-group textarea {
    padding: 0.75rem;
    border: 2px solid #ecf0f1;
    border-radius: 5px;
    font-family: inherit;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.form-group small {
    color: #95a5a6;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.ai-response {
    background: var(--light);
    padding: 1.5rem;
    border-radius: 5px;
    line-height: 1.8;
    color: var(--text);
    max-height: 600px;
    overflow-y: auto;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.btn-secondary {
    display: inline-block;
    padding: 10px 25px;
    background: #95a5a6;
    color: var(--white);
    text-decoration: none;
    border-radius: 20px;
    margin-left: 1rem;
    border: none;
    cursor: pointer;
    font-weight: 600;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

@media (max-width: 768px) {
    .customize-content {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .customize-container h1 {
        font-size: 1.8rem;
    }
}
</style>

<script>
document.getElementById('customizeForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const destination = document.getElementById('destination').value;
    const budget = parseFloat(document.getElementById('budget').value);
    const duration = parseInt(document.getElementById('duration').value);
    const travelers = parseInt(document.getElementById('travelers').value);
    const preferences = document.getElementById('preferences').value;
    
    const budgetPerPerson = Math.round(budget / travelers);
    
    try {
        const response = await fetch('<?php echo SITE_URL; ?>/api/customize-tour.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                destination: destination,
                budget: budgetPerPerson,
                duration: duration,
                preferences: preferences
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('aiResponse').textContent = data.itinerary;
            document.getElementById('resultSection').style.display = 'block';
            window.customTourData = {
                destination: destination,
                budget: budget,
                duration: duration,
                travelers: travelers,
                itinerary: data.itinerary
            };
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error generating tour plan: ' + error.message);
    }
});

function bookCustomTour() {
    window.location.href = '<?php echo SITE_URL; ?>/pages/book-custom-tour.php';
}

function resetForm() {
    document.getElementById('customizeForm').reset();
    document.getElementById('resultSection').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
