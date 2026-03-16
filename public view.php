<?php
require 'config.php';

// Get all projects
$projects_result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");

// Get all skills
$skills_result = $conn->query("SELECT * FROM skills_experiences ORDER BY experience_level DESC, skill_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo PORTFOLIO_NAME; ?> - Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .navbar {
            background-color: #0066cc;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header-section h1 {
            margin-top: 0;
            color: #333;
            font-size: 32px;
        }

        .header-section .subtitle {
            color: #666;
            font-size: 16px;
            margin: 10px 0;
        }

        .header-section .contact-info {
            color: #999;
            font-size: 14px;
            margin-top: 10px;
        }

        .header-section .contact-info a {
            color: #0066cc;
            text-decoration: none;
        }

        .section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #0066cc;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .project-card {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            padding: 20px;
            border-radius: 6px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .project-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .project-card h3 {
            color: #0066cc;
            margin-top: 0;
        }

        .project-card p {
            color: #666;
            margin: 8px 0;
            line-height: 1.5;
        }

        .project-meta {
            font-size: 13px;
            color: #999;
            margin: 10px 0;
        }

        .project-technologies {
            margin-top: 10px;
        }

        .tech-tag {
            display: inline-block;
            background-color: #e3f2fd;
            color: #0066cc;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .project-url {
            margin-top: 10px;
        }

        .project-url a {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .project-url a:hover {
            background-color: #0052a3;
        }

        .skills-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .skill-card {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 6px;
        }

        .skill-card h3 {
            color: #0066cc;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .skill-level {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .skill-details {
            margin-top: 10px;
            color: #666;
            font-size: 13px;
        }

        .skill-description {
            margin-top: 10px;
            color: #666;
            font-style: italic;
        }

        .empty-message {
            text-align: center;
            color: #999;
            padding: 30px;
            font-size: 16px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0066cc;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Portfolio</h1>
        <a href="index.php">← Back to Dashboard</a>
    </div>

    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <h1><?php echo htmlspecialchars(PORTFOLIO_NAME); ?></h1>
            <p class="subtitle">Professional Portfolio</p>
            <div class="contact-info">
                <p>@<?php echo htmlspecialchars(PORTFOLIO_USERNAME); ?></p>
                <p><a href="mailto:<?php echo htmlspecialchars(PORTFOLIO_EMAIL); ?>"><?php echo htmlspecialchars(PORTFOLIO_EMAIL); ?></a></p>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="section">
            <h2>📁 Projects</h2>
            <?php if ($projects_result->num_rows > 0): ?>
                <div class="projects-grid">
                    <?php while ($project = $projects_result->fetch_assoc()): ?>
                        <div class="project-card">
                            <h3><?php echo htmlspecialchars($project['project_title']); ?></h3>
                            <p><?php echo htmlspecialchars($project['project_description']); ?></p>
                            
                            <?php if ($project['start_date'] || $project['end_date']): ?>
                                <div class="project-meta">
                                    <strong>Duration:</strong>
                                    <?php 
                                    echo $project['start_date'] ? date('M Y', strtotime($project['start_date'])) : ''; 
                                    echo ($project['start_date'] && $project['end_date']) ? ' - ' : '';
                                    echo $project['end_date'] ? date('M Y', strtotime($project['end_date'])) : 'Present';
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="project-technologies">
                                <strong>Technologies:</strong> <br>
                                <?php
                                $techs = explode(',', $project['technologies']);
                                foreach ($techs as $tech) {
                                    echo '<span class="tech-tag">' . htmlspecialchars(trim($tech)) . '</span>';
                                }
                                ?>
                            </div>
                            
                            <?php if ($project['project_url']): ?>
                                <div class="project-url">
                                    <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank">View Project →</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    No projects added yet.
                </div>
            <?php endif; ?>
        </div>

        <!-- Skills & Experiences Section -->
        <div class="section">
            <h2>🎯 Skills & Experiences</h2>
            <?php if ($skills_result->num_rows > 0): ?>
                <div class="skills-container">
                    <?php while ($skill = $skills_result->fetch_assoc()): ?>
                        <div class="skill-card">
                            <h3><?php echo htmlspecialchars($skill['skill_name']); ?></h3>
                            <span class="skill-level"><?php echo htmlspecialchars($skill['experience_level']); ?></span>
                            
                            <div class="skill-details">
                                <?php if ($skill['years_of_experience']): ?>
                                    <strong>Experience:</strong> <?php echo (int)$skill['years_of_experience']; ?> year<?php echo $skill['years_of_experience'] !== '1' ? 's' : ''; ?>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($skill['description']): ?>
                                <p class="skill-description"><?php echo htmlspecialchars($skill['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    No skills added yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
