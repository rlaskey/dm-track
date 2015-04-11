<?php require BASEPATH.'views/layout/top.php';
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */ ?>
<table id="data">
<thead><tr>
	<th>day</th><th>time</th><th>value</th><th>notes</th>
</tr></thead>
<tbody id="tbody"></tbody>
</table>

<?php $inputSave = '<input type="submit" name="save" value="'.
	_('save').'" />'.PHP_EOL; ?>

<script type="text/template" id="row-glucose-template">
<td><%- day %></td>
<td><%- hm %></td>
<td style="<%- Views.glucoseStyle(value) %>"><%- value %></td>
<td><%- notes %></td>
</script>


<script type="text/template" id="row-insulin-template">
<td><%- day %></td>
<td><%- hm %></td>
<td><%- value %></td>
<td><%- notes %></td>
</script>


<script type="text/template" id="glucose-template">
<legend>glucose<%- glucose_id > 0 ? ':#'+glucose_id : '+' %></legend>

<input type="number" name="value" min="0" placeholder="83" required
	value="<%- value %>" />
<input type="datetime-local" name="time" required
	value="<%- Views.localDateTime(time) %>" />
<textarea cols="8" rows="2" name="notes" maxlength="83"><%- notes %></textarea>

<?= $inputSave ?>
</script>


<script type="text/template" id="insulin-template">
<legend>insulin<%- insulin_id > 0 ? ':#'+insulin_id : '+' %></legend>

<input type="number" name="units" min="0" placeholder="0" required
	value="<%- units %>" />

<select name="type">
<?php foreach (\Models\Insulin::$columns['type']['options'] AS $option): ?>
<option value="<?= $option ?>"><?= $option ?></option>
<?php endforeach; ?>
</select>

<textarea cols="8" rows="2" name="notes" maxlength="83"><%- notes %></textarea>
<input type="datetime-local" name="time" required
	value="<%- Views.localDateTime(time) %>" />

<?= $inputSave ?>
</script>


<script type="text/template" id="range-template">
<legend>range:</legend>

<input required type="text" name="start" value="<%- start %>"
	placeholder="<?= \Config::$defaultRange['start'] ?>" />
-&gt;
<input required type="text" name="stop" value="<%- stop %>"
	placeholder="<?= \Config::$defaultRange['stop'] ?>" />

<input type="submit" name="save" value="<?= _('go') ?>" />
</script>

<?php require BASEPATH.'views/layout/bottom.php';
