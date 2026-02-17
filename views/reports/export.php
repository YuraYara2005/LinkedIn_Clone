<?php require APPROOT . '/views/reused/header.php'; ?>

<style>
    .report-container {
        max-width: 1128px;
        margin: 0 auto;
        padding: 24px;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .report-table th, .report-table td {
        border: 1px solid #E9ECEF;
        padding: 8px;
        text-align: left;
    }

    .report-table th {
        background-color: #F3F2EF;
        color: #1A1A1A;
    }

    .btn-back {
        background: #0A66C2;
        color: #FFFFFF;
        border: none;
        padding: 8px 16px;
        border-radius: 24px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #004182;
    }
</style>

<div class="report-container">
    <h1><?php echo htmlspecialchars($data['title']); ?></h1>
    <a href="<?php echo URLROOT; ?>/profiles/view" class="btn-back mb-3">Back to Profile</a>
    <table class="report-table">
        <thead>
            <tr>
                <?php foreach ($data['headers'] as $header): ?>
                    <th><?php echo htmlspecialchars($header); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($data['report_data']): ?>
                <?php foreach ($data['report_data'] as $row): ?>
                    <tr>
                        <?php foreach ($data['fields'] as $field): ?>
                            <td><?php echo htmlspecialchars($row->$field ?? ''); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo count($data['headers']); ?>">No data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pdfBase64 = '<?php echo $_SESSION['pdf_base64']; ?>';
        const filename = '<?php echo $_SESSION['pdf_filename']; ?>';
        if (pdfBase64) {
            const link = document.createElement('a');
            link.href = 'data:application/pdf;base64,' + pdfBase64;
            link.download = filename;
            link.click();
        }
        // Clean up session data
        <?php unset($_SESSION['pdf_base64']); ?>
        <?php unset($_SESSION['pdf_filename']); ?>
    });
</script>

<?php require APPROOT . '/views/reused/footer.php'; ?>