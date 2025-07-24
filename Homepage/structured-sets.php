<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Structured Sets - GeneSwim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridlex/2.7.1/gridlex.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="structured-sets.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
<?php require_once 'navbar.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Sets</h1>
        </div>
    </section>

    <!-- Sets Content -->
    <main class="sets-content">
        <div class="sets-container">

            <!-- Sprints Set -->
            <article class="set-item" data-set="sprints">
                <div class="set-image">
                    <img src="https://ext.same-assets.com/4193966861/3844417856.jpeg" alt="Sprints Swimming Training" loading="lazy">
                </div>
                <div class="set-content">
                    <h2 class="set-title">Sprints</h2>
                    <p class="set-description">
                        This high-intensity set targets maximum speed, acceleration, and anaerobic power.
                        Short intervals (25–50m) are performed at race pace or faster, with full or near-full
                        rest to allow for peak effort each repetition. Focus is on explosive starts, fast
                        turnover, and efficient breakouts. Ideal for sharpening racing performance and
                        building fast-twitch muscle strength.
                    </p>
                    <div class="set-details">
                        <span class="set-duration-label">Duration</span>
                        <span class="set-duration">Flexible</span>
                    </div>
                    <button class="btn-read-more" data-target="sprints">Read More</button>
                </div>
            </article>

            <!-- Hypoxic Set -->
            <article class="set-item" data-set="hypoxic">
                <div class="set-image">
                    <img src="https://ext.same-assets.com/4193966861/2988967394.jpeg" alt="Hypoxic Training Underwater" loading="lazy">
                </div>
                <div class="set-content">
                    <h2 class="set-title">Hypoxic</h2>
                    <p class="set-description">
                        This breath-control set is designed to improve lung capacity, discipline underwater,
                        and boost efficiency between breaths. Swimmers follow restricted breathing patterns
                        (e.g., breathe every 3/5/7/9 strokes) or complete short distances with limited or
                        no breaths. Hypoxic training develops mental toughness, enhances CO₂ max, and
                        supports better oxygen capacity.
                    </p>
                    <div class="set-details">
                        <span class="set-duration-label">Duration</span>
                        <span class="set-duration">Flexible</span>
                    </div>
                    <button class="btn-read-more" data-target="hypoxic">Read More</button>
                </div>
            </article>

            <!-- Rehab Set -->
            <article class="set-item" data-set="rehab">
                <div class="set-image">
                    <img src="https://ext.same-assets.com/4193966861/4221476503.jpeg" alt="Rehabilitation Swimming" loading="lazy">
                </div>
                <div class="set-content">
                    <h2 class="set-title">Rehab</h2>
                    <p class="set-description">
                        A low-intensity recovery-based set that promotes joint mobility, muscle activation,
                        and stroke mechanics while reducing load on injured areas. Movements are controlled
                        and smooth, with extra attention to shoulder-friendly strokes like backstroke and
                        modified freestyle. This set avoids paddles and intense kicking to prioritize
                        gentle engagement and safe form restoration.
                    </p>
                    <div class="set-details">
                        <span class="set-duration-label">Duration</span>
                        <span class="set-duration">Flexible</span>
                    </div>
                    <button class="btn-read-more" data-target="rehab">Read More</button>
                </div>
            </article>

            <!-- Aerobics Set -->
            <article class="set-item" data-set="aerobics">
                <div class="set-image">
                    <img src="https://ext.same-assets.com/4193966861/4097219043.jpeg" alt="Aerobic Swimming Training" loading="lazy">
                </div>
                <div class="set-content">
                    <h2 class="set-title">Aerobics</h2>
                    <p class="set-description">
                        This set emphasizes sustained swimming at a moderate pace to build cardiovascular
                        endurance, stroke rhythm, and pacing strategy. Repeats are typically 100–400m with
                        short rest intervals to maintain a steady heart rate. Swimmers are expected to hold
                        consistent times and technique, focusing on distance per stroke, efficient turns,
                        and relaxed breathing.
                    </p>
                    <div class="set-details">
                        <span class="set-duration-label">Duration</span>
                        <span class="set-duration">Flexible</span>
                    </div>
                    <button class="btn-read-more" data-target="aerobics">Read More</button>
                </div>
            </article>

            <!-- Sprint Medley Set -->
            <article class="set-item" data-set="sprint-medley">
                <div class="set-image">
                    <img src="https://ext.same-assets.com/4193966861/4245787591.jpeg" alt="Sprint Medley Swimming" loading="lazy">
                </div>
                <div class="set-content">
                    <h2 class="set-title">Sprint (medley)</h2>
                    <p class="set-description">
                        This sprint-focused IM (Individual Medley) set trains explosive transitions across
                        all four competitive strokes: butterfly, backstroke, breaststroke, and freestyle.
                        Short distances are performed at sprint pace to sharpen versatility, refine
                        stroke-specific power, and improve coordination under fatigue. Expect high effort,
                        rapid transitions, and precise technique under pressure.
                    </p>
                    <div class="set-details">
                        <span class="set-duration-label">Duration</span>
                        <span class="set-duration">Flexible</span>
                    </div>
                    <button class="btn-read-more" data-target="sprint-medley">Read More</button>
                </div>
            </article>

        </div>
    </main>

<?php require_once 'footer.php'; ?>

    <!-- Set Detail Modal -->
    <div id="setModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle" class="modal-title"></h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalImage" class="modal-image"></div>
                <div id="modalDescription" class="modal-description"></div>
                <div id="modalBenefits" class="modal-benefits"></div>
                <div id="modalInstructions" class="modal-instructions"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-start-workout">Start This Workout</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="structured-sets.js"></script>
</body>
</html>