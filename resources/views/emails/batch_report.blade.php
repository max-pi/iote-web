<html>
	<body>
		<p>Hello,</p>
		<p>
			A batch of beacons was created.<br>
			Here are the results of the execution.
		</p>
		<p>
			<table>
				<thead style="text-align:left;">
					<tr>
						<th>Batch ID</th>
						<th>Size</th>
						<th>Attached User</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="padding: 2.5px 12.5px;">{{ $batch->_id }}</td>
						<td style="padding: 2.5px 12.5px;">{{ count($batch->beacons) }} beacons</td>
						<td style="padding: 2.5px 12.5px;">{{ $batch->user ?: 'N.A.' }}</td>
					</tr>
				</tbody>
				<thead style="text-align:left;">
					<tr>
						<th>Beacon IDs</th>
					</tr>
				</thead>
			</table>
			<div>
				@foreach($batch->beacons as $beaconId)
				<span style="padding: 2.5px 12.5px;">{{ $beaconId }}</span>
				@endforeach
			</div>
		</p>
		<p>
			Good luck!<br>
			IOTE Development
		</p>
	</body>
</html>