<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$error_message = '';
$success_message = '';
$edit_skill = null;

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $skill_name = $conn->real_escape_string($_POST['skill_name']);
    $experience_level = $conn->real_escape_string($_POST['experience_level']);
    $years_of_experience = !empty($_POST['years_of_experience']) ? (int)$_POST['years_of_experience'] : null;
    $description = $conn->real_escape_string($_POST['description']);

    if (empty($skill_name) || empty($experience_level)) {
        $error_message = "Skill name and experience level are required!";
    } else {
        $years_sql = $years_of_experience ? $years_of_experience : "NULL";
        if ($conn->query("INSERT INTO skills_experiences (skill_name, experience_level, years_of_experience, description) VALUES ('$skill_name', '$experience_level', $years_sql, '$description')")) {
            $success_message = "Skill added successfully!";
        } else {
            $error_message = "Error adding skill!";
        }
    }
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $skill_id = (int)$_POST['skill_id'];
    $skill_name = $conn->real_escape_string($_POST['skill_name']);
    $experience_level = $conn->real_escape_string($_POST['experience_level']);
    $years_of_experience = !empty($_POST['years_of_experience']) ? (int)$_POST['years_of_experience'] : null;
    $description = $conn->real_escape_string($_POST['description']);

    if (empty($skill_name) || empty($experience_level)) {
        $error_message = "Skill name and experience level are required!";
    } else {
        $years_sql = $years_of_experience ? $years_of_experience : "NULL";
        if ($conn->query("UPDATE skills_experiences SET skill_name='$skill_name', experience_level='$experience_level', years_of_experience=$years_sql, description='$description' WHERE skill_id=$skill_id")) {
            $success_message = "Skill updated successfully!";
        } else {
            $error_message = "Error updating skill!";
        }
    }
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $skill_id = (int)$_POST['skill_id'];
    if ($conn->query("DELETE FROM skills_experiences WHERE skill_id=$skill_id")) {
        $success_message = "Skill deleted successfully!";
    } else {
        $error_message = "Error deleting skill!";
    }
}

// Handle Edit (Load existing skill)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $skill_id = (int)$_POST['skill_id'];
    $result = $conn->query("SELECT * FROM skills_experiences WHERE skill_id=$skill_id");
    if ($result->num_rows > 0) {
        $edit_skill = $result->fetch_assoc();
    }
}

// Get all skills
$skills_result = $conn->query("SELECT * FROM skills_experiences ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills & Experiences - Portfolio Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
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

        .form-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-section h2 {
            margin-top: 0;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 80px;
            font-family: Arial, sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 5px rgba(0, 102, 204, 0.3);
        }

        .form-buttons {
            display: flex;
            gap: 10px;
        }

        .form-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #0066cc;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0052a3;
        }

        .btn-secondary {
            background-color: #666;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #555;
        }

        .skills-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .skills-section h2 {
            margin-top: 0;
            color: #333;
        }

        .skill-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .skill-item h3 {
            margin-top: 0;
            color: #0066cc;
        }

        .skill-item p {
            margin: 8px 0;
            color: #666;
        }

        .skill-meta {
            font-size: 13px;
            color: #999;
            margin-top: 10px;
        }

        .skill-badge {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 8px;
        }

        .skill-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .skill-actions button {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: background-color 0.3s;
        }

        .btn-edit {
            background-color: #4caf50;
            color: white;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete {
            background-color: #d32f2f;
            color: white;
        }

        .btn-delete:hover {
            background-color: #b71c1c;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .no-skills {
            text-align: center;
            color: #999;
            padding: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Manage Skills & Experiences</h1>
        <a href="index.php">← Back to Dashboard</a>
    </div>

    <div class="container">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Add/Edit Skill Form -->
        <div class="form-section">
            <h2><?php echo $edit_skill ? 'Edit Skill' : 'Add New Skill'; ?></h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $edit_skill ? 'update' : 'create'; ?>">
                <?php if ($edit_skill): ?>
                    <input type="hidden" name="skill_id" value="<?php echo $edit_skill['skill_id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="skill_name">Skill Name *</label>
                        <input 
                            type="text" 
                            id="skill_name" 
                            name="skill_name" 
                            placeholder="e.g. PHP, JavaScript, MySQL"
                            value="<?php echo $edit_skill ? htmlspecialchars($edit_skill['skill_name']) : ''; ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="experience_level">Experience Level *</label>
                        <select id="experience_level" name="experience_level" required>
                            <option value="">-- Select Level --</option>
                            <option value="Beginner" <?php echo $edit_skill && $edit_skill['experience_level'] === 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                            <option value="Intermediate" <?php echo $edit_skill && $edit_skill['experience_level'] === 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                            <option value="Advanced" <?php echo $edit_skill && $edit_skill['experience_level'] === 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
                            <option value="Expert" <?php echo $edit_skill && $edit_skill['experience_level'] === 'Expert' ? 'selected' : ''; ?>>Expert</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="years_of_experience">Years of Experience</label>
                    <input 
                        type="number" 
                        id="years_of_experience" 
                        name="years_of_experience" 
                        placeholder="Enter number of years"
                        min="0"
                        value="<?php echo $edit_skill && $edit_skill['years_of_experience'] ? $edit_skill['years_of_experience'] : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="Describe your experience with this skill"
                    ><?php echo $edit_skill ? htmlspecialchars($edit_skill['description']) : ''; ?></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primary">
                        <?php echo $edit_skill ? 'Update Skill' : 'Add Skill'; ?>
                    </button>
                    <?php if ($edit_skill): ?>
                        <button type="reset" class="btn-secondary" onclick="location.reload();">Cancel</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Skills List -->
        <div class="skills-section">
            <h2>Your Skills & Experiences</h2>
            <?php if ($skills_result->num_rows > 0): ?>
                <?php while ($skill = $skills_result->fetch_assoc()): ?>
                    <div class="skill-item">
                        <h3><?php echo htmlspecialchars($skill['skill_name']); ?></h3>
                        <span class="skill-badge"><?php echo htmlspecialchars($skill['experience_level']); ?></span>
                        <?php if ($skill['years_of_experience']): ?>
                            <p><strong>Experience:</strong> <?php echo (int)$skill['years_of_experience']; ?> year<?php echo $skill['years_of_experience'] !== '1' ? 's' : ''; ?></p>
                        <?php endif; ?>
                        <?php if ($skill['description']): ?>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($skill['description']); ?></p>
                        <?php endif; ?>
                        <div class="skill-meta">
                            Added: <?php echo date('M d, Y', strtotime($skill['created_at'])); ?>
                        </div>
                        <div class="skill-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="skill_id" value="<?php echo $skill['skill_id']; ?>">
                                <button type="submit" class="btn-edit">Edit</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="skill_id" value="<?php echo $skill['skill_id']; ?>">
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-skills">
                    <p>No skills added yet. Add your first skill above!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
