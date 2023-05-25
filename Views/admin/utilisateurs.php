<table style="margin-bottom: 200px;" class="table table-striped">
    <thead>
        <th>ID</th>
        <th>Email</th>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->email ?></td>
                <td><strong><?= $user->roles ?></strong></td>
                
                <td>
                    <a href="/admin/supprimeUser/<?= $user->id ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>