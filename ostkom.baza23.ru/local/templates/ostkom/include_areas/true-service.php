          <!-- true service-->
          <div class="true-service module">
            <!-- title-->
            <div class="true-service__title h2">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/true-service-title1.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Заголовок")
		);?> 			
			</div>
            <!-- subtitle-->
            <div class="true-service__subtitle h3">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/true-service__subtitle.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Подзаголовок")
		);?> 				
			</div>
            <!-- features-->
            <ul class="true-service__features features ug-grid ug-block-mobile6 ug-block-desktop3">
              <!-- item-->
              <li class="features__item ug-col">
                <div class="features__item-icon">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-icon1.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"1-я иконка")
		);?> 				
                </div>
                <div class="features__item-body">
                  <div class="features__item-desc">
                    <p>
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-desc1.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Подпись к 1-й иконке")
		);?> 					
					</p>
                  </div>
                </div>
              </li>
              <!-- item-->
              <li class="features__item ug-col">
                <div class="features__item-icon">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-icon2.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"2-я иконка")
		);?> 				
                </div>
                <div class="features__item-body">
                  <div class="features__item-desc">
                    <p>
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-desc2.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Подпись к 2-й иконке")
		);?> 						
					</p>
                  </div>
                </div>
              </li>
              <!-- item-->
              <li class="features__item ug-col">
                <div class="features__item-icon">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-icon3.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"3-я иконка")
		);?> 				
                </div>
                <div class="features__item-body">
                  <div class="features__item-desc">
                    <p>
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-desc3.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Подпись к 3-й иконке")
		);?> 						
					</p>
                  </div>
                </div>
              </li>
              <!-- item-->
              <li class="features__item ug-col">
                <div class="features__item-icon">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-icon4.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"4-я иконка")
		);?> 				
                </div>
                <div class="features__item-body">
                  <div class="features__item-desc">
                    <p>
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/features__item-desc4.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Подпись к 4-й иконке")
		);?> 					
					</p>
                  </div>
                </div>
              </li>
            </ul>
          </div>