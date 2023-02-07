<div class="benefit module">
	<div class="benefit__inner">
		 <!-- body-->
		<div class="benefit__body">
			<div class="benefit__title h2">
				 <?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/benefit_home_title.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Текст в рамке.Заголовок")
		);?>
			</div>
			<div class="benefit__desc">
				<p>
					 <?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/benefit_home_text.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Текст в рамке")
		);?>
				</p>
			</div>
		</div>
	</div>
</div>
<br>