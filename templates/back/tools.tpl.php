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
      <td>Photos</td>
      <td><?= $this->data["photoTools"] ?></td>
      <td>
        <button type="button" id="remove-photo">Remove</button>
      </td>
      <td id="remove-photo-result"></td>
    </tr>
    <tr>
      <td>Database</td>
      <td><?= $this->data["databaseTools"] ?></td>
      <td>
        <button type="button" id="check-database">Action</button>
      </td>
      <td id="check-database-result"></td>
    </tr>
    </tbody>
  </table>
</div>
