<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Redirect to login page if not an admin
    header("Location: ../../logins/logout.php"); // Change the path as needed
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f7f9fc;
            position: relative;
        }

        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            padding: 24px 0;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 0 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            height: 100px;
            width: auto;
        }

        .nav-section {
            margin-bottom: 16px;
        }

        .nav-group {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8a94a6;
            font-weight: 600;
            padding: 8px 24px;
            margin-bottom: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            cursor: pointer;
            color: #4d5a6f;
            text-decoration: none;
            justify-content: space-between;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background-color: #f3f4f6;
            color: #1e293b;
        }

        .nav-item.active {
            background-color: #e9f7ee;
            color: #00843d;
            border-left: 3px solid #00843d;
            font-weight: 600;
        }

        .nav-item-content {
            display: flex;
            align-items: center;
        }

        .nav-icon {
            font-size: 16px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
        }

        .nav-icon1 {
            font-size: 18px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
        }

        .nav-item span {
            font-size: 14px;
        }

        .arrow-icon {
            font-size: 12px;
            opacity: 0.6;
        }

        .user-profile {
            margin-top: auto;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            border-top: 1px solid #f1f1f5;
            background-color: #fafbfc;
        }

        .user-profile .avatar {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background-color: #e9f7ee;
            color: #00843d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 16px;
        }

        .user-profile .user-info {
            flex-grow: 1;
        }

        .user-profile .name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }

        .user-profile .role {
            font-size: 12px;
            color: #64748b;
        }

        .user-profile .logout {
            cursor: pointer;
            color: #64748b;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .page-content {
            flex-grow: 1;
            padding: 24px;
            position: relative;
        }

        /* Stepper Navigation */
        .stepper-nav {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            background-color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }

        .step {
            display: flex;
            align-items: center;
            color: #64748b;
            font-size: 14px;
            position: relative;
        }

        .step-active {
            font-weight: 700;
            color: #1e293b;
        }

        .step-divider {
            margin: 0 12px;
            color: #cbd5e1;
            font-size: 14px;
        }

        .step-icon {
            font-size: 14px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 8px;
        }

        .step-current .step-icon {
            background-color: #00843d;
            color: white;
        }

        .step-completed .step-icon {
            background-color: #e9f7ee;
            color: #00843d;
        }

        .step-pending .step-icon {
            background-color: #f1f5f9;
            color: #94a3b8;
        }

        .back-btn {
            background-color: transparent;
            color: #64748b;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .report-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            padding: 24px;
            margin-bottom: 24px;
        }

        .report-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .report-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background-color: #e9f7ee;
            color: #00843d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            font-size: 18px;
        }

        .report-title-container {
            flex-grow: 1;
        }

        .report-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .report-meta {
            font-size: 14px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .report-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .report-content {
            min-height: 300px;
            border-radius: 8px;
            padding: 20px;
        }

        .user-info .email {
            font-size: 13px;
            font-weight: 400;
            color: #777;
            margin-top: 2px;
        }

        /* New styles for report form */
        .trader-form {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            background-color: #fafbfc;
        }

        .form-section {
            margin-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #00843d;
            margin-bottom: 10px;
        }

        .trader-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 10px;
        }

        .trader-info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .info-value {
            height: 30px;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            background-color: #f8fafc;
            color: #334155;
            font-weight: 500;
            font-size: 13px;
        }


        .category-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 10px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .report-table th,
        .report-table td {
            border: 2px solid #cbd5e1;
            padding: 8px;
            text-align: center;
        }

        .report-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 600;
        }

        .report-table td {
            background-color: #f8fafc;
            color: #94a3b8;
            height: 30px;
        }

        .table-header {
            background-color: #e9f7ee !important;
            color: #00843d !important;
            font-weight: 600;
        }

        .table-subheader {
            background-color: #f1f5f9 !important;
        }
        
        /* Remarks section styles */
        .remarks-section {
            margin-top: 15px;
        }
        
        .remarks-label {
            font-size: 12px;
            color: #000000;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .remarks-textarea {
            width: 100%;
            min-height: 80px;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            background-color: #f8fafc;
            color: #334155;
            font-size: 13px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../../resources/SRA_thumbnail.png" alt="Agricultural Logo">
        </div>
        
        <div class="nav-section">
            <div class="nav-group">DASHBOARD</div>
            
            <a href="traderlist.php" class="nav-item active" id="tradersNav">
                <div class="nav-item-content">
                    <div class="nav-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span>Traders</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <a href="supplydemand.php" class="nav-item" id="supplyDemandNav">
                <div class="nav-item-content">
                    <div class="nav-icon1">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                    </div>
                    <span>Supply and Demand</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            
        </div>
        
        <div class="nav-section">
            <div class="nav-group">PREFERENCES</div>
            <a href="accountsetting.php" class="nav-item" id="accountSettingsNav">
                <div class="nav-item-content">
                    <div class="nav-icon">
                        <i class="fa-solid fa-gear"></i>
                    </div>
                    <span>Account Settings</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
        </div>
        
        <div class="user-profile">
            <div class="avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="user-info">
                <div class="name"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
                <div class="email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                <div class="role">Administrator</div>
            </div>
            <div class="logout" id="logoutBtn">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
        </div>
    </div>
        
    <div class="page-content">
        <!-- Stepper Navigation -->
        <div class="stepper-nav">
            <div class="step step-completed">
                <div class="step-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span>List of Traders</span>
            </div>
            <div class="step-divider">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="step step-completed">
                <div class="step-icon">
                    <i class="fa-solid fa-list"></i>
                </div>
                <span>List of Reports</span>
            </div>
            <div class="step-divider">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="step step-current step-active">
                <div class="step-icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <span>Report</span>
            </div>
        </div>
        
        <button class="back-btn" id="backToReports">
            <i class="fa-solid fa-arrow-left"></i>
            Back to List of Reports
        </button>

        <div class="report-container">
            <div class="report-header">
                <div class="report-icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <div class="report-title-container">
                    <h2 class="report-title" id="reportTitle">Monthly Sales Report</h2>
                    <div class="report-meta">
                        <div class="report-meta-item">
                            <i class="fa-solid fa-user" style="color: #64748b;"></i>
                            <span id="traderName">Jane Cooper</span>
                        </div>
                        <div class="report-meta-item">
                            <i class="fa-solid fa-location-dot" style="color: #64748b;"></i>
                            <span id="traderRegion">Region V</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="report-content">
                <form class="trader-form">
                    <div class="form-section">
                        <div class="section-title">Trader Information</div>
                        <div class="trader-info-grid">
                            <div class="trader-info-item">
                                <div class="info-label">Name of Trader</div>
                                <div class="info-value"></div>
                            </div>
                            <div class="trader-info-item">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value"></div>
                            </div>
                            <div class="trader-info-item">
                                <div class="info-label">Business Address</div>
                                <div class="info-value"></div>
                            </div>
                            <div class="trader-info-item">
                                <div class="info-label">Crop Year</div>
                                <div class="info-value"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="section-title">Category & Stock Information</div>
                        <div class="category-grid">
                            <div class="trader-info-item">
                                <div class="info-label">Category</div>
                                <div class="info-value"></div>
                            </div>
                            <div class="trader-info-item">
                                <div class="info-label">Previous Crop Stock Balance</div>
                                <div class="info-value"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="section-title">Report Data</div>
                        <div style="overflow-x: auto;">
                            <table class="report-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">CY/MONTH</th>
                                        <th colspan="4" class="table-header">PURCHASES (VOLUME)</th>
                                        <th rowspan="2" class="table-subheader">Pls specify Sources & other information</th>
                                        <th colspan="5" class="table-header">UTILIZATION (VOLUME)</th>
                                        <th rowspan="2">Stock Balance</th>
                                    </tr>
                                    <tr>
                                        <th>IMPORTATION</th>
                                        <th>LOCAL MILLS</th>
                                        <th>LOCAL TRADERS</th>
                                        <th>AUCTION (BOC)</th>
                                        <th>OWN USED/ MANUFACTURING</th>
                                        <th>SALE TO DOMESTIC MARKET</th>
                                        <th>EXPORT TO US MARKET</th>
                                        <th>EXPORT TO WORLD MARKET</th>
                                        <th>Clients, & other information</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- New Remarks Section -->
                    <div class="form-section">
                      
                        <div class="remarks-section">
                            <div class="remarks-label">Remarks</div>
                            <textarea class="remarks-textarea" readonly></textarea>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Get URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const reportID = urlParams.get('ID');
        const traderName = urlParams.get('trader');
        const traderRegion = urlParams.get('region');
        const traderID = urlParams.get('traderID');
        
        // Set report information
        document.getElementById('traderName').textContent = traderName;
        document.getElementById('traderRegion').textContent = traderRegion;
        
        // Back button
        document.getElementById("backToReports").addEventListener("click", function() {
            window.location.href = `trader-report-list.php?trader=${traderName}&region=${traderRegion}&traderID=${traderID}`;
        });
        
        // Logout functionality
        document.getElementById("logoutBtn").addEventListener("click", function() {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "../../logins/logout.php";
            }
        });
    </script>
</body>
</html>
