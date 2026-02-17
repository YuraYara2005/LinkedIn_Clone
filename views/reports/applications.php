<?php require APPROOT . '/views/reused/header.php'; ?>

<style>
    .report-container {
        max-width: 1128px;
        margin: 0 auto;
        padding: 24px;
        background: #F3F2EF;
    }

    .report-card {
        background: #FFFFFF;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        margin-bottom: 16px;
    }

    .report-header {
        padding: 16px 24px;
        border-bottom: 1px solid #E9ECEF;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .report-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1A1A1A;
        margin: 0;
    }

    .report-body {
        padding: 24px;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    .report-table th, .report-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #E9ECEF;
    }

    .report-table th {
        background: #F8F9FA;
        font-weight: 600;
        color: #1A1A1A;
    }

    .report-table td {
        color: #1A1A1A;
    }

    .btn-download, .btn-back {
        font-size: 14px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 24px;
        text-decoration: none;
        margin-left: 8px;
    }

    .btn-download {
        background: #0A66C2;
        border-color: #0A66C2;
        color: #FFFFFF;
    }

    .btn-download:hover {
        background: #004182;
        border-color: #004182;
    }

    .btn-back {
        background: #6C757D;
        border-color: #6C757D;
        color: #FFFFFF;
    }

    .btn-back:hover {
        background: #5A6268;
        border-color: #5A6268;
    }
</style>

<div class="report-container">
    <div class="report-card">
        <div class="report-header">
            <h2><?php echo htmlspecialchars($data['title']); ?></h2>
            <div>
                <a href="<?php echo URLROOT; ?>/profiles/showProfile/<?php echo $_SESSION['user_id']; ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a>
                <a href="<?php echo URLROOT; ?>/reports/export/applications" class="btn-download">
                    <i class="fas fa-download"></i> Download as PDF
                </a>
            </div>
        </div>
        <div class="report-body">
            <?php if (isset($data['report_data']) && !empty($data['report_data'])): ?>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['report_data'] as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row->title); ?></td>
                                <td><?php echo htmlspecialchars($row->company); ?></td>
                                <td><?php echo htmlspecialchars($row->location); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row->applied_date))); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($row->status)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No job applications data available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>