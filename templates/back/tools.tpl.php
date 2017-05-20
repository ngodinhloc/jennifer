<div class="admin-content">
  <table>
    <thead>
    <tr>
      <th>Tools</th>
      <th>Data</th>
      <th>Action</th>
      <th width="25%">Result</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Remove unused photos</td>
      <td>
      </td>
      <td>
        <button type="button" id="remove-photo">Remove</button>
      </td>
      <td id="remove-photo-result"></td>
    </tr>
    <tr>
      <td>Check Database tables</td>
      <td>
        <input type="radio" name="checkdb" value="<?= ANALYZE_DB; ?>"> Analyze
        <input type="radio" name="checkdb" value="<?= OPTIMIZE_DB; ?>"> Optimize
        <input type="radio" name="checkdb" value="<?= CHECK_DB; ?>"> Check
        <input type="radio" name="checkdb" value="<?= REPAIR_DB; ?>"> Repair
      </td>
      <td>
        <button type="button" id="check-database">Action</button>
      </td>
      <td id="check-database-result"></td>
    </tr>
    </tbody>
  </table>
</div>
