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
$edit_project = null;

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = $conn->real_escape_string($_POST['project_title']);
    $description = $conn->real_escape_string($_POST['project_description']);
    $technologies = $conn->real_escape_string($_POST['technologies']);
    $project_url = $conn->real_escape_string($_POST['project_url']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    if (empty($title) || empty($description) || empty($technologies)) {
        $error_message = "Title, description, and technologies are required!";
    } else {
        $start_date_sql = $start_date ? "'$start_date'" : "NULL";
        $end_date_sql = $end_date ? "'$end_date'" : "NULL";
        
        if ($conn->query("INSERT INTO projects (project_title, project_description, technologies, project_url, start_date, end_date) VALUES ('$title', '$description', '$technologies', '$project_url', $start_date_sql, $end_date_sql)")) {
            $success_message = "Project added successfully!";
        } else {
            $error_message = "Error adding project!";
        }
    }
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $project_id = (int)$_POST['project_id'];
    $title = $conn->real_escape_string($_POST['project_title']);
    $description = $conn->real_escape_string($_POST['project_description']);
    $technologies = $conn->real_escape_string($_POST['technologies']);
    $project_url = $conn->real_escape_string($_POST['project_url']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    if (empty($title) || empty($description) || empty($technologies)) {
        $error_message = "Title, description, and technologies are required!";
    } else {
        $start_date_sql = $start_date ? "'$start_date'" : "NULL";
        $end_date_sql = $end_date ? "'$end_date'" : "NULL";
        
        if ($conn->query("UPDATE projects SET project_title='$title', project_description='$description', technologies='$technologies', project_url='$project_url', start_date=$start_date_sql, end_date=$end_date_sql WHERE project_id=$project_id")) {
            $success_message = "Project updated successfully!";
        } else {
            $error_message = "Error updating project!";
        }
    }
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $project_id = (int)$_POST['project_id'];
    if ($conn->query("DELETE FROM projects WHERE project_id=$project_id")) {
        $success_message = "Project deleted successfully!";
    } else {
        $error_message = "Error deleting project!";
    }
}

// Handle Edit (Load existing project)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $project_id = (int)$_POST['project_id'];
    $result = $conn->query("SELECT * FROM projects WHERE project_id=$project_id");
    if ($result->num_rows > 0) {
        $edit_project = $result->fetch_assoc();
    }
}

// Get all projects
$projects_result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - Portfolio Manager</title>
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
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 100px;
            font-family: Arial, sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus {
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

        .projects-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .projects-section h2 {
            margin-top: 0;
            color: #333;
        }

        .project-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .project-item h3 {
            margin-top: 0;
            color: #0066cc;
        }

        .project-item p {
            margin: 8px 0;
            color: #666;
        }

        .project-meta {
            font-size: 13px;
            color: #999;
            margin-top: 10px;
        }

        .project-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .project-actions button {
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

        .no-projects {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Manage Projects</h1>
        <a href="index.php">← Back to Dashboard</a>
    </div>

    <div class="container">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Add/Edit Project Form -->
        <div class="form-section">
            <h2><?php echo $edit_project ? 'Edit Project' : 'Add New Project'; ?></h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $edit_project ? 'update' : 'create'; ?>">
                <?php if ($edit_project): ?>
                    <input type="hidden" name="project_id" value="<?php echo $edit_project['project_id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="project_title">Project Title *</label>
                    <input 
                        type="text" 
                        id="project_title" 
                        name="project_title" 
                        placeholder="Enter project title"
                        value="<?php echo $edit_project ? htmlspecialchars($edit_project['project_title']) : ''; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="project_description">Description *</label>
                    <textarea 
                        id="project_description" 
                        name="project_description" 
                        placeholder="Enter project description"
                        required
                    ><?php echo $edit_project ? htmlspecialchars($edit_project['project_description']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="technologies">Technologies Used *</label>
                    <input 
                        type="text" 
                        id="technologies" 
                        name="technologies" 
                        placeholder="e.g. PHP, MySQL, JavaScript, HTML, CSS"
                        value="<?php echo $edit_project ? htmlspecialchars($edit_project['technologies']) : ''; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="project_url">Project URL</label>
                    <input 
                        type="url" 
                        id="project_url" 
                        name="project_url" 
                        placeholder="https://example.com"
                        value="<?php echo $edit_project ? htmlspecialchars($edit_project['project_url']) : ''; ?>"
                    >
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input 
                            type="date" 
                            id="start_date" 
                            name="start_date"
                            value="<?php echo $edit_project && $edit_project['start_date'] ? $edit_project['start_date'] : ''; ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input 
                            type="date" 
                            id="end_date" 
                            name="end_date"
                            value="<?php echo $edit_project && $edit_project['end_date'] ? $edit_project['end_date'] : ''; ?>"
                        >
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primary">
                        <?php echo $edit_project ? 'Update Project' : 'Add Project'; ?>
                    </button>
                    <?php if ($edit_project): ?>
                        <button type="reset" class="btn-secondary" onclick="location.reload();">Cancel</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Projects List -->
        <div class="projects-section">
            <h2>Your Projects</h2>
            <?php if ($projects_result->num_rows > 0): ?>
                <?php while ($project = $projects_result->fetch_assoc()): ?>
                    <div class="project-item">
                        <h3><?php echo htmlspecialchars($project['project_title']); ?></h3>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($project['project_description']); ?></p>
                        <p><strong>Technologies:</strong> <?php echo htmlspecialchars($project['technologies']); ?></p>
                        <?php if ($project['project_url']): ?>
                            <p><strong>URL:</strong> <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank"><?php echo htmlspecialchars($project['project_url']); ?></a></p>
                        <?php endif; ?>
                        <?php if ($project['start_date'] || $project['end_date']): ?>
                            <p><strong>Duration:</strong> 
                                <?php 
                                echo $project['start_date'] ? date('M Y', strtotime($project['start_date'])) : ''; 
                                echo ($project['start_date'] && $project['end_date']) ? ' - ' : '';
                                echo $project['end_date'] ? date('M Y', strtotime($project['end_date'])) : 'Present';
                                ?>
                            </p>
                        <?php endif; ?>
                        <div class="project-meta">
                            Created: <?php echo date('M d, Y', strtotime($project['created_at'])); ?>
                        </div>
                        <div class="project-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
                                <button type="submit" class="btn-edit">Edit</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-projects">
                    <p>No projects yet. Add your first project above!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
    <?php
    $result = $conn->query("SELECT * FROM Author_Tbl");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Author_ID']}</td>
                <td>{$row['Author_FName']}</td>
                <td>{$row['Author_LName']}</td>
                <td>{$row['Author_Country']}</td>
                <td>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='{$row['Author_ID']}'>
                        <button type='submit'>Delete</button>
                    </form>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='edit_id' value='{$row['Author_ID']}'>
                        <button type='submit'>Edit</button>
                    </form>
                </td>
              </tr>";
    }
    ?>
</body>
</html>
