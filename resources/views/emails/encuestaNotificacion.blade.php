<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Portfolio - Responsive Email Template</title>
		<style type="text/css">
			/* ----- Custom Font Import ----- */
			@import url(https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic&subset=latin,latin-ext);

			/* ----- Text Styles ----- */
			table{
				font-family: 'Lato', Arial, sans-serif;
				-webkit-font-smoothing: antialiased;
				-moz-font-smoothing: antialiased;
				font-smoothing: antialiased;
			}

			@media only screen and (max-width: 700px){
				/* ----- Base styles ----- */
				.full-width-container{
					padding: 0 !important;
				}

				.container{
					width: 100% !important;
				}

				/* ----- Header ----- */
				.header td{
					padding: 30px 15px 30px 15px !important;
				}

				/* ----- Projects list ----- */
				.projects-list{
					display: block !important;
				}

				.projects-list tr{
					display: block !important;
				}

				.projects-list td{
					display: block !important;
				}

				.projects-list tbody{
					display: block !important;
				}

				.projects-list img{
					margin: 0 auto 25px auto;
				}

				/* ----- Half block ----- */
				.half-block{
					display: block !important;
				}

				.half-block tr{
					display: block !important;
				}

				.half-block td{
					display: block !important;
				}

				.half-block__image{
					width: 100% !important;
					background-size: cover;
				}

				.half-block__content{
					width: 100% !important;
					box-sizing: border-box;
					padding: 25px 15px 25px 15px !important;
				}

				/* ----- Hero subheader ----- */
				.hero-subheader__title{
					padding: 80px 15px 15px 15px !important;
					font-size: 35px !important;
				}

				.hero-subheader__content{
					padding: 0 15px 90px 15px !important;
				}

				/* ----- Title block ----- */
				.title-block{
					padding: 0 15px 0 15px;
				}

				/* ----- Paragraph block ----- */
				.paragraph-block__content{
					padding: 25px 15px 18px 15px !important;
				}

				/* ----- Info bullets ----- */
				.info-bullets{
					display: block !important;
				}

				.info-bullets tr{
					display: block !important;
				}

				.info-bullets td{
					display: block !important;
				}

				.info-bullets tbody{
					display: block;
				}

				.info-bullets__icon{
					text-align: center;
					padding: 0 0 15px 0 !important;
				}

				.info-bullets__content{
					text-align: center;
				}

				.info-bullets__block{
					padding: 25px !important;
				}

				/* ----- CTA block ----- */
				.cta-block__title{
					padding: 35px 15px 0 15px !important;
				}

				.cta-block__content{
					padding: 20px 15px 27px 15px !important;
				}

				.cta-block__button{
					padding: 0 15px 0 15px !important;
				}
			}
		</style>

		<!--[if gte mso 9]><xml>
			<o:OfficeDocumentSettings>
				<o:AllowPNG/>
				<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml><![endif]-->
	</head>

	<body style="padding: 0; margin: 0;" bgcolor="#eeeeee">
		<span style="color:transparent !important; overflow:hidden !important; display:none !important; line-height:0px !important; height:0 !important; opacity:0 !important; visibility:hidden !important; width:0 !important; mso-hide:all;">{{$encuesta}}</span>

		<!-- / Full width container -->
		<table class="full-width-container" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bgcolor="#eeeeee" style="width: 100%; height: 100%; padding: 30px 0 30px 0;">
			<tr>
				<td align="center" valign="top">
					<!-- / 700px container -->
					<table class="container" border="0" cellpadding="0" cellspacing="0" width="700" bgcolor="#ffffff" style="width: 700px;">
						<tr>
							<td align="center" valign="top">
								<!-- / Header -->
								<table class="container header" border="0" cellpadding="0" cellspacing="0" width="620" style="width: 620px;">
									<tr>
										<td style="padding: 10px 0 10px 0; border-bottom: solid 1px #eeeeee;" align="left">
											<a href="#" style="font-size: 30px; text-decoration: none; color: #000000;">{{$nombre}}</a>
										</td>
									</tr>
								</table>
								<!-- /// Header -->

								<!-- / Hero subheader -->
								<table class="container hero-subheader" border="0" cellpadding="0" cellspacing="0" width="620" style="width: 620px;">
									<tr>
										<td class="hero-subheader__title" style="font-size: 43px; font-weight: bold; padding: 40px 0 15px 0;" align="left">{{$encuesta}}</td>
									</tr>

									<tr>
										<td class="hero-subheader__content" style="font-size: 16px; line-height: 27px; color: #969696; padding: 0 10px 10px 0;" align="left">
											Has sido invitada para completar la encuesta de Comunicación Interna, por favor haz click en el link para completarla.
										</td>
									</tr>
									<tr>
										<td class="hero-subheader__content" style="font-size: 16px; line-height: 27px; color: #969696; padding: 0 10px 10px 0;" align="left">
											<a href="#">{{$link}}</a>
										</td>
									</tr>									
								</table>
								<!-- /// Hero subheader -->

								<!-- / Title -->
								<table class="container title-block" border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td align="center" valign="top">
											<table class="container" border="0" cellpadding="0" cellspacing="0" width="620" style="width: 620px;">
												<tr>
													<td style="border-bottom: solid 1px #eeeeee; padding: 35px 0 18px 0; font-size: 26px;" align="left">Muchas Gracias</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!-- /// Title -->

								<!-- / Projects list -->
								<table class="container projects-list" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top: 25px;">
									<tr>
										<td>
											<table class="container" border="0" cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td align="left">
														<a href="#"><img src="http://elsoftpy.com/elsoftpy_assets/logos-gnb.png" width="705" height="165" border="0" style="display: block;"></a>
													</td>


												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!-- /// Projects list -->



								<!-- / Footer -->
								<table class="container" border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
									<tr>
										<td align="center">
											<table class="container" border="0" cellpadding="0" cellspacing="0" width="620" align="center" style="border-top: 1px solid #eeeeee; width: 620px;">
												<tr>
													<td align="middle">
														<table width="60" height="2" border="0" cellpadding="0" cellspacing="0" style="width: 60px; height: 2px;">
															<tr>
																<td align="middle" width="60" height="2" style="padding: 10px 0 0 0; width: 60px; height: 2px; font-size: 1px;"><img src="http://elsoftpy.com/elsoftpy_assets/elsoft.png" style="width:100%;"></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td style="text-align: center; padding: 20px 0 10px 0;">
														<a href="#" style="font-size: 28px; text-decoration: none; color: #d5d5d5;">ElSoft - PollPal</a>
													</td>
												</tr>

												<tr>
													<td style="color: #d5d5d5; text-align: center; font-size: 15px; padding: 10px 0 60px 0; line-height: 22px;">Copyright &copy; 2017 <a href="http://elsoftpy.com/" target="_blank" style="text-decoration: none; border-bottom: 1px solid #d5d5d5; color: #d5d5d5;">ElSoft</a>. <br />All rights reserved.</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!-- /// Footer -->
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>