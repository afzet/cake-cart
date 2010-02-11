		<div id="content">
			<div class="topsellers view">
				<h2>View Topseller</h2>				
				<dl>
					<dt class="altrow">Id</dt>
					<dd class="altrow"><?=$data['Product']['id']?></dd>
			
					<dt>Sku</dt>
					<dd class="altrow"><?=$data['Product']['sku']?></dd>
			
					<dt class="altrow">Name</dt>
					<dd class="altrow"><?=$data['Product']['name']?></dd>
			
					<dt>Cost</dt>
					<dd class="altrow"><?=$data['Product']['cost']?></dd>
					
					<dt class="altrow">Image</dt>
					<dd class="altrow"><?=$data['Product']['iamge']?></dd>
			
					<dt>Rank</dt>
					<dd class="altrow"><?=$data['Product']['rank']?></dd>
			
					<dt class="altrow">Posted Amazon</dt>
					<dd class="altrow"><?=$data['Product']['posted_amazon']?></dd>
				</dl>
			</div>fdsfds
		<div class="actions">
			<ul>
				<li><a href="/topsellers/edit/477">Edit Topseller</a> </li>
				<li><a href="/topsellers/delete/477" onclick="return confirm('Are you sure you want to delete #<?=$data['Product']['id']?>?');">Delete Topseller</a> </li>
				<li><a href="/topsellers">List Topsellers</a> </li>		
				<li><a href="/topsellers/add">New Topseller</a> </li>
			</ul>
		</div>
