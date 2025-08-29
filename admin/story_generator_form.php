<?php
// Re-use the pre-defined list of voices
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
        <h3>Story Audio Generator</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <strong>How to use this tool:</strong>
            <ol>
                <li>Paste your full script into the text area below. Ensure each character's dialogue is preceded by their name in all caps, followed by a colon (e.g., <code>NARRATORE:</code>, <code>LEO:</code>).</li>
                <li>In the "Characters & Voices" section, define each character from your script. The name must be an exact match (e.g., "NARRATORE").</li>
                <li>Assign a voice, pitch, and speed for each character.</li>
                <li>Provide a base filename for the output files. They will be numbered sequentially (e.g., my_story_01.mp3, my_story_02.mp3).</li>
                <li>Click "Generate Story Audio".</li>
            </ol>
        </div>

        <form method="post" action="admin.php?section=story-generator&action=generate">

            <div class="mb-3">
                <label for="script_content" class="form-label">Full Story Script</label>
                <textarea class="form-control" id="script_content" name="script_content" rows="15" required></textarea>
            </div>

            <h4 class="mt-4">Characters & Voices</h4>
            <table class="table" id="characters_table">
                <thead>
                    <tr>
                        <th>Character Name (e.g., NARRATORE)</th>
                        <th>Voice</th>
                        <th>Speaking Rate (0.25-4.0)</th>
                        <th>Pitch (-20.0 to 20.0)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="characters_tbody">
                    <!-- Character rows will be added here by JavaScript -->
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addCharacterRow()">+ Add Character</button>

            <hr>

            <div class="mb-3">
                <label for="output_basename" class="form-label">Output Filename Base</label>
                <input type="text" class="form-control" id="output_basename" name="output_basename" required>
                <div class="form-text">Example: "sinfonia_mezzanotte". Files will be saved as "sinfonia_mezzanotte_01.mp3", etc.</div>
            </div>

            <button type="submit" class="btn btn-primary">Generate Story Audio</button>
        </form>
    </div>
</div>

<script>
function addCharacterRow() {
    const tbody = document.getElementById('characters_tbody');
    const newRow = document.createElement('tr');

    const index = tbody.rows.length;

    newRow.innerHTML = `
        <td>
            <input type="text" name="characters[${index}][name]" class="form-control" placeholder="NARRATORE" required>
        </td>
        <td>
            <select name="characters[${index}][voice]" class="form-select">
                <?php foreach ($italian_voices as $voice_code => $voice_desc): ?>
                    <option value="<?= htmlspecialchars($voice_code) ?>"><?= htmlspecialchars($voice_desc) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="number" name="characters[${index}][rate]" class="form-control" value="1.0" step="0.05" min="0.25" max="4.0">
        </td>
        <td>
            <input type="number" name="characters[${index}][pitch]" class="form-control" value="0.0" step="0.5" min="-20" max="20">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeCharacterRow(this)">Remove</button>
        </td>
    `;

    tbody.appendChild(newRow);
}

function removeCharacterRow(button) {
    const row = button.closest('tr');
    row.remove();
    // Note: This doesn't re-index the form input array names, but PHP handles non-sequential keys fine.
}

// Add one character row by default to get the user started
document.addEventListener('DOMContentLoaded', function() {
    addCharacterRow();
});
</script>
