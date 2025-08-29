<?php
// Pre-defined list of high-quality Italian voices
$italian_voices = [
    'it-IT-Wavenet-A' => 'Italian (Female, Wavenet A)',
    'it-IT-Wavenet-B' => 'Italian (Male, Wavenet B)',
    'it-IT-Wavenet-C' => 'Italian (Female, Wavenet C)',
    'it-IT-Wavenet-D' => 'Italian (Male, Wavenet D)',
    'it-IT-Standard-A' => 'Italian (Female, Standard A)',
    'it-IT-Standard-B' => 'Italian (Male, Standard B)',
    'it-IT-Standard-C' => 'Italian (Female, Standard C)',
    'it-IT-Standard-D' => 'Italian (Male, Standard D)',
];
?>

<div class="card">
    <div class="card-header">
        <h3>Google Cloud Text-to-Speech</h3>
    </div>
    <div class="card-body">
        <p>Use this tool to convert text into high-quality audio files using Google's API.</p>
        <form method="post" action="admin.php?section=audio-generator&action=generate">

            <div class="mb-3">
                <label for="text_content" class="form-label">Text to Convert</label>
                <textarea class="form-control" id="text_content" name="text_content" rows="10" required></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="voice_name" class="form-label">Voice</label>
                    <select id="voice_name" name="voice_name" class="form-select">
                        <?php foreach ($italian_voices as $voice_code => $voice_desc): ?>
                            <option value="<?= htmlspecialchars($voice_code) ?>"><?= htmlspecialchars($voice_desc) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                 <div class="col-md-3">
                    <label for="speaking_rate" class="form-label">Speaking Rate (0.25 to 4.0)</label>
                    <input type="number" class="form-control" id="speaking_rate" name="speaking_rate" value="1.0" step="0.05" min="0.25" max="4.0" required>
                </div>
                <div class="col-md-3">
                    <label for="pitch" class="form-label">Pitch (-20.0 to 20.0)</label>
                    <input type="number" class="form-control" id="pitch" name="pitch" value="0.0" step="0.5" min="-20" max="20" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="output_filename" class="form-label">Output Filename (e.g., "narrator_part1")</label>
                <input type="text" class="form-control" id="output_filename" name="output_filename" required>
                 <div class="form-text">The file will be saved as .mp3 in the 'audio/' directory.</div>
            </div>


            <button type="submit" class="btn btn-primary">Generate Audio File</button>
        </form>
    </div>
</div>
