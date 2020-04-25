<?php


namespace App\Service;


use App\Entity\Meet;
use App\Entity\User;

class InviteSender {
    private $mailer;
    private $from = "atoevents@atoevents.ru";

    public function __construct(\Swift_Mailer $mailer) {
        $this->mailer = $mailer;
    }

    public function sendPassword(User $user, string $password) {
        $message = new \Swift_Message("🎥 «Преодоление COVID-19 индустрией транспорта»:");
        $message->setFrom($this->from)
            ->setTo($user->getLogin())
            ->setBody( $this->makePasswordMessage($user->getLogin(), $password), 'text/html');
        $this->mailer->send($message);
    }

    public function sendPasswordRepair(User $user) {
        $message = new \Swift_Message("«Online-meeting system notification");
        $message->setFrom($this->from)
            ->setTo($user->getLogin())
            ->setBody( "For generation of new password follow the link please <a href='http://live.covid-transport.ru/api/users/repair2/".base64_encode($user->getLogin())."'>link</a>", 'text/html');
        $this->mailer->send($message);
    }

    public function makePasswordMessage($login, $password){
        return "
        <style type=\"text/css\">
			a[x-apple-data-detectors]{color: inherit !important; text-decoration: none !important;}
			a[href^=\"tel\"]:hover{text-decoration: none !important;}
			table td{mso-line-height-rule: exactly;}
			a img{border: none;}
			b, strong{font-weight:700;}
			p{margin:0;}
			th{padding: 0;}
			td{text-decoration: none;}
			a{
				outline: none;
				color: #fa5151;
				text-decoration: underline;
			}
			.highlight-phone{
				color:inherit; 
				border:none;
			}
			.nl span,
			.nl a{
				color:inherit !important; 
				text-decoration:none !important; 
				border:none !important;
			}
			a:hover{text-decoration: none !important;}
			.h-u a{text-decoration: none;}
			.h-u a:hover{text-decoration: underline !important;}
			.btn-01:hover{background-color: #e8e7e6 !important;}
			.active a:hover{opacity: 0.8;}
			.btn-01,
			.active a{transition: all 0.3s ease;}
			ul{Margin: 0 0 0 20px; padding:0;}
			img + div {
				display:none !important;
				width:0px !important;
				height:0px !important;
				opacity:0 !important;
			}
			@media only screen and (max-width:375px) and (min-width:374px){
				.gmail-fix{min-width:374px !important;}
			}
			@media only screen and (max-width:414px) and (min-width:413px){
				.gmail-fix{min-width:413px !important;}
			}
			@media only screen and (max-width:500px){
				/* default style */
				.flexible{width: 100% !important;}
				.img-flex img{width: 100% !important; height: auto !important;}
				.table-holder{display: table !important; width: 100% !important;}
				.thead{display: table-header-group !important; width: 100% !important;}
				.tfoot{display: table-footer-group !important; width: 100% !important;}
				.tflex{display: block !important; width: 100% !important;}
				.hide{display: none !important; width: 0 !important; height: 0 !important; padding: 0 !important; font-size: 0 !important; line-height: 0 !important;}
				
				.tc{margin: 0 auto !important; float: none !important;}
				.ac{text-align: center !important;}
				.h-0{height: 0 !important;}
				
				.p-0{padding: 0 !important;}
				.p-20{padding: 20px !important;}
				.p-30{padding: 30px !important;}

				.plr-0{padding-left: 0 !important; padding-right: 0 !important;}
				.plr-10{padding-left: 10px !important; padding-right: 10px !important;}
				.plr-15{padding-left: 15px !important; padding-right: 15px !important;}
				.plr-20{padding-left: 20px !important; padding-right: 20px !important;}

				.pt-20{padding-top: 20px !important;}
				.pt-30{padding-top: 30px !important;}

				.pb-20{padding-bottom: 20px !important;}
				.pb-30{padding-bottom: 30px !important;}
				/* custom style */
				.vat{vertical-align: top !important;}
				.w-i80 img{width: 80px !important; height: auto !important;}
				.w-i115 img{width: 115px !important; height: auto !important;}
				.fs-20{font-size: 20px !important; line-height: 24px !important;}
				.fs-24{font-size: 24px !important; line-height: 28px !important;}
				
			}
		</style>
		<table class=\"gmail-fix\" bgcolor=\"#fafafa\" width=\"100%\" style=\"min-width:320px;\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
				<td>
					<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
						<tr>
							<td style=\"display:none; font-size:0; line-height:0; width:0; height:0; padding:0;\">
								
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table class=\"flexible\" width=\"600\" align=\"center\" style=\"margin:0 auto;\" cellpadding=\"0\" cellspacing=\"0\">

						<tr>
							<td bgcolor=\"#ffffff\">
								<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">

									

									<tr>
										<td class=\"plr-15\" style=\"padding: 25px 80px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
												<tr>
													<td class=\"w-i115\">
														<a target=\"blank\" href=\"https://www.events.ato.ru/\"><img src=\"https://261520.selcdn.ru/images/1390039/103117logo-02.png\" style=\"vertical-align:top; height: auto; width: 115px;\" border=\"0\" width=\"115\" alt=\"ATO EVENTS\" /></a>
													</td>
												
												</tr>
											</table>
										</td>
									</tr>
		
					<tr>
							<td class=\"img-flex\">
								<a target=\"blank\" href=\"http://live.covid-transport.ru/\"><img src=\"https://261520.selcdn.ru/images/1390039/2main.png\" style=\"vertical-align:top; height: auto; width:600px;\" border=\"0\" width=\"600\" alt=\"ПРЕОДОЛЕНИЕ COVID-19
ИНДУСТРИЕЙ ТРАНСПОРТА\" /></a>
							</td>
						</tr>
						                <tr>
										<td class=\"plr-15\" style=\"padding: 30px 80px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
												<tr>
													<td class=\"pb-20\" style=\"font:22px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 20px;\">
														Dear friends! 
													</td>
												</tr>

												<tr>
													<td class=\"pb-20\" style=\"font:14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 20px;\">
													Online broadcast of <span class=\"h-u\" style=\"color:#2a58c4; text-decoration: none; font-weight: bold;\">Overcoming COVID-19 for Transport Industry</span> conference starts at 11 a.m. (UTC+03:00, Moscow)
													</td>
												</tr>
												<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 40px;\">
													<b>Join us online and get maximum benefits.</b></td>
												</tr>
												
													<tr>
													<td>
														<table align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td class=\"active\" align=\"center\" style=\"font:bold 12px/14px Arial, Helvetica, sans-serif; color:#fff; mso-padding-alt:20px 30px; letter-spacing: 2px; text-transform: uppercase;\" bgcolor=\"#ffb415\">
																	<a target=\"_blank\" style=\"text-decoration:none; color:#fffffe; display:block; padding:20px 30px;\" href=\"http://live.covid-transport.ru/\">Join broadcast</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												
													<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 10px;\">
													 
													</td>
												</tr>
													<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 10px;\">
													<br/><b>Login: ".$login."</b>
</td>
												</tr>
													<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 20px;\">
													<b>Password: ".$password."</b>
</td>
												</tr>
														<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 30px;\">
												Simultaneous translation provided. 
													</td>
												</tr>
												
														<tr>
													<td>
														<table align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td class=\"active\" align=\"center\" style=\"font:bold 12px/14px Arial, Helvetica, sans-serif; color:#fff; mso-padding-alt:20px 30px; letter-spacing: 2px; text-transform: uppercase;\" bgcolor=\"#ffb415\">
																	<a target=\"_blank\" style=\"text-decoration:none; color:#fffffe; display:block; padding:20px 30px;\" href=\"https://drive.google.com/drive/folders/1_WOkQlOed4nLdSKkUcko-rd-sJVUVVst\">Agenda</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												
														<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 0px;\">
													<br/>
													Do you have any questions? <br/> Please check <span class=\"h-u\"><a href=\"https://www.events.ato.ru/faq2\" target=\"_blank\" style=\"color:#2a58c4; text-decoration: none; font-weight: bold;\">FAQ</a></span> or connect with our managers: 
													</td>
												</tr>
											</table>
										</td>
									</tr>





										<tr>
										<td class=\"plr-15\" style=\"padding: 30px 80px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
												
												<tr>
													<td class=\"plr-0\" style=\"padding: 0 0px 20px;\">
														<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td width=\"55%\" style=\"font:14px/28px Arial, Helvetica, sans-serif; color:#212121;\">
																	<b style=\"color:#2a58c4;\">Technical support</b> <br />
																	<span class=\"nl\">Amir Manzhukov</span><br/>
																	<span class=\"nl\">+7 962 945-51-29</span><br/>
																	<span class=\"nl\"><a href=\"mailto:a.manzhukov@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">a.manzhukov@atoevents.ru</a></span><br/><br/>
																	<span class=\"nl\">Anna Torokhova</span><br/>
<span class=\"nl\">+7 964 539-83-98</span><br/>
<span class=\"nl\"><a href=\"mailto:a.torokhova@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">a.torokhova@atoevents.ru</a></span><br/>

																</td>
																
																	<td style=\"font:14px/28px Arial, Helvetica, sans-serif; color:#212121;\">
																	<b style=\"color:#2a58c4;\">Online meeting software </b> <br />
																	<span class=\"nl\">Valeria Burskaya</span><br/>
																	<span class=\"nl\">+7 965 109-30-88</span><br/>
																	<span class=\"nl\"><a href=\"mailto:v.burskaya@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">v.burskaya@atoevents.ru</a></span><br/><br/>
																
																<span class=\"nl\">Elizabeth Slavskaya</span><br/>
 <span class=\"nl\">+7 967 118-68-31</span><br/>
<span class=\"nl\"><a href=\"mailto: e.slavskaya@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">e.slavskaya@atoevents.ru</a></span><br/>
																
																
																</td>
															
															</tr>
														</table>
													</td>
												</tr>
	                                     
											
											</table>
										</td>
									</tr>
							
										<tr>
										<td class=\"plr-15\" style=\"padding: 30px 80px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
											<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 0px;\">
													Conference materials will be available shortly.
 
													</td>
												</tr>
												</table>
										</td>
									</tr>
											<tr>
										<td class=\"plr-15\" style=\"padding: 30px 75px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
											<tr>
													<td style=\"padding: 0 0 00px;\">
														<table width=\"200\" align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td bgcolor=\"#dcdde1\" height=\"2\" style=\"font-size:1px; line-height:1px; color:#dcdde1;\"> </td>
															</tr>
														</table>
													</td>
												</tr>
												</table>
										</td>
									</tr>

									<tr>
										<td class=\"plr-15\" style=\"padding: 30px 80px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
												<tr>
													<td class=\"pb-20\" style=\"font:22px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 20px;\">
														Здравствуйте, друзья! 
													</td>
												</tr>

												<tr>
													<td class=\"pb-20\" style=\"font:14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 20px;\">
													Уже совсем скоро, 30 марта в 11:00 ждем Вас на онлайн-конференции <span class=\"h-u\" style=\"color:#2a58c4; text-decoration: none; font-weight: bold;\">«Преодоление COVID-19 индустрией транспорта»</span>.
													</td>
												</tr>
												<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 40px;\">
													<b>Приходите онлайн и получите максимум пользы.</b></td>
												</tr>
												
													<tr>
													<td>
														<table align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td class=\"active\" align=\"center\" style=\"font:bold 12px/14px Arial, Helvetica, sans-serif; color:#fff; mso-padding-alt:20px 30px; letter-spacing: 2px; text-transform: uppercase;\" bgcolor=\"#ffb415\">
																	<a target=\"_blank\" style=\"text-decoration:none; color:#fffffe; display:block; padding:20px 30px;\" href=\"http://live.covid-transport.ru/\">Подключится к трансляции</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												
													<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 10px;\">
													 
													</td>
												</tr>
													<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 10px;\">
													<br/><b>Логин: ".$login."</b>
</td>
												</tr>
													<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 20px;\">
													<b>Пароль: ".$password."</b>
</td>
												</tr>
														<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 30px;\">
													Трансляция пройдет с синхронным переводом.
													</td>
												</tr>
												
														<tr>
													<td>
														<table align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td class=\"active\" align=\"center\" style=\"font:bold 12px/14px Arial, Helvetica, sans-serif; color:#fff; mso-padding-alt:20px 30px; letter-spacing: 2px; text-transform: uppercase;\" bgcolor=\"#ffb415\">
																	<a target=\"_blank\" style=\"text-decoration:none; color:#fffffe; display:block; padding:20px 30px;\" href=\"https://drive.google.com/drive/folders/1PwOvSs8rGl_30OY4Ahnq76ZUGX0f1iLI\">Программа</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												
														<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 0px;\">
													<br/>У вас остались вопросы по онлайн-конференции?<br/> Смотрите раздел <span class=\"h-u\"><a href=\"https://www.events.ato.ru/faq1\" target=\"_blank\" style=\"color:#2a58c4; text-decoration: none; font-weight: bold;\">часто задаваемые вопросы</a></span> или свяжитесь с нашими специалистами: 
													</td>
												</tr>
												
												
													
												
											
										
	                                       
											
											</table>
										</td>
									</tr>





										<tr>
										<td class=\"plr-15\" style=\"padding: 30px 80px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
												
												<tr>
													<td class=\"plr-0\" style=\"padding: 0 0px 20px;\">
														<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td width=\"55%\" style=\"font:14px/28px Arial, Helvetica, sans-serif; color:#212121;\">
																	<b style=\"color:#2a58c4;\">Техническая поддержка </b> <br />
																	<span class=\"nl\">Никита Качаев</span><br/>
																	<span class=\"nl\">+7 964 500-25-33</span><br/>
																	<span class=\"nl\"><a href=\"mailto:n.kachaev@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">n.kachaev@atoevents.ru</a></span><br/><br/>
																	<span class=\"nl\">Светлана Пименова</span><br/>
<span class=\"nl\">+7 903 263-46-14</span><br/>
<span class=\"nl\"><a href=\"mailto:s.pimenova@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">s.pimenova@atoevents.ru</a></span><br/>

																</td>
																
																	<td style=\"font:14px/28px Arial, Helvetica, sans-serif; color:#212121;\">
																	<b style=\"color:#2a58c4;\">Назначение онлайн-встреч </b> <br />
																	<span class=\"nl\">Алена  Гараничева</span><br/>
																	<span class=\"nl\">+7 965 109-34-84</span><br/>
																	<span class=\"nl\"><a href=\"mailto:a.garanicheva@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">a.garanicheva@atoevents.ru</a></span><br/><br/>
																
																<span class=\"nl\"> Маргарита Гукасова</span><br/>
 <span class=\"nl\">+7 967 198-38-81</span><br/>
<span class=\"nl\"><a href=\"mailto:m.gukasova@atoevents.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">m.gukasova@atoevents.ru</a></span><br/>
																
																
																</td>
															
															</tr>
														</table>
													</td>
												</tr>
	                                     
											
											</table>
										</td>
									</tr>
										<tr>
										<td class=\"plr-15\" style=\"padding: 30px 75px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
											<tr>
													<td style=\"padding: 0 0 00px;\">
														<table width=\"200\" align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td bgcolor=\"#dcdde1\" height=\"2\" style=\"font-size:1px; line-height:1px; color:#dcdde1;\"> </td>
															</tr>
														</table>
													</td>
												</tr>
												</table>
										</td>
									</tr>
										<tr>
										<td class=\"plr-15\" style=\"padding: 30px 80px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
											<tr>
													<td class=\"pb-20\" style=\"font: 14px/28px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 0px;\">
													Бухгалтерские документы будут направлены вам на почту.<br/>
                                                    Доступ к материалам мероприятия будет открыт в ближайшее время.
 
													</td>
												</tr>
												</table>
										</td>
									</tr>
								
										
									


									<tr>
										<td class=\"plr-15\" style=\"padding: 30px 75px;\">
											<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
												<tr>
													<td align=\"center\" style=\"padding: 0 0 30px;\">
														<a target=\"blank\" href=\"https://www.events.ato.ru/\"><img src=\"https://261520.selcdn.ru/images/1390039/145165logo-03.png\" style=\"vertical-align:top; height: auto; width: 134px;\" border=\"0\" width=\"134\" alt=\"ATO EVENTS\" /></a>
													</td>
												</tr>

												<tr>
													<td style=\"padding: 0 0 30px;\">
														<table width=\"200\" align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td bgcolor=\"#dcdde1\" height=\"2\" style=\"font-size:1px; line-height:1px; color:#dcdde1;\"> </td>
															</tr>
														</table>
													</td>
												</tr>

												<tr>
													<td style=\"padding: 0 0 30px;\">
														<table align=\"center\" style=\"margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td class=\"active\">
																	<a target=\"blank\" href=\"https://www.youtube.com/channel/UCLhTiGNY4iQDekFWR0Qd_Ww\"><img src=\"https://261520.selcdn.ru/images/1390039/14030ico-yt.png\" style=\"vertical-align:top; height: auto; width: 27px;\" border=\"0\" width=\"27\" alt=\"YT\" /></a>
																</td>
																<td width=\"45\"></td>
																<td class=\"active\">
																	<a target=\"blank\" href=\"https://www.facebook.com/ATOEvents?fref=ts\"><img src=\"https://261520.selcdn.ru/images/1390039/16676ico-fb.png\" style=\"vertical-align:top; height: auto; width: 24px;\" border=\"0\" width=\"24\" alt=\"FB\" /></a>
																</td>
															</tr> 
														</table>
													</td>
												</tr>

												<tr>
													<td align=\"center\" style=\"font:bold 20px/24px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 10px;\">
														<a href=\"tel:74951085143\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">7 (495) 108-5143</a>
													</td>
												</tr>
												<tr>
													<td class=\"h-u\" align=\"center\" style=\"font:bold 16px/20px Arial, Helvetica, sans-serif; color:#212121; padding: 0 0 30px;\">
														<a href=\"mailto:events@ato.ru\" target=\"_blank\" style=\"color:#212121; text-decoration: none;\">events@ato.ru</a>
													</td>
												</tr>

												<tr>
													<td class=\"h-u\" align=\"center\" style=\"font:12px/16px Arial, Helvetica, sans-serif; color:#b3b3b3;\">
														Если вы не хотите больше получать наши письма, перейдите <a class=\"unsub_link\" href=\"\" target=\"_blank\" style=\"color:#b3b3b3; text-decoration: none;\">по ссылке</a>
                                        
													</td>
												</tr>
											</table>
										</td>
									</tr>

								</table>
							</td>
						</tr>
						
					</table>
				</td>
			</tr>
		</table>
";
    }

    public function sendInvite(Meet $meet) {
        $message = new \Swift_Message("Вы приглашены на встречу/You are invited to the individual online-meeting during");
        $message->setFrom($this->from)
            ->setTo($meet->getGuest()->getLogin())
            ->setBody( $this->makeText($meet), 'text/html');
        $this->mailer->send($message);
    }

    public function sendInviteResponse(Meet $meet) {
            $message = new \Swift_Message("Online-meeting system notification");
        $message->setFrom($this->from)
            ->setTo($meet->getCreator()->getLogin())
            ->setBody( $this->makeTextResponse($meet), 'text/html');
        $this->mailer->send($message);
    }

    public function sendInviteResponseControl(Meet $meet) {
        $message = new \Swift_Message("Online-meeting system notification");
        $message->setFrom($this->from)
            ->setTo("A.lotrya@atoevents.ru")
            ->setBody( $this->makeTextResponseControl($meet), 'text/html');
        $this->mailer->send($message);
        $message = new \Swift_Message("Online-meeting system notification");
        $message->setFrom($this->from)
            ->setTo("nik_mak@bk.ru")
            ->setBody( $this->makeTextResponseControl($meet), 'text/html');
        $this->mailer->send($message);
    }

    private function makeText(Meet $meet){
        return "
            Dear " . $meet->getGuest()->getName() . " " . $meet->getGuest()->getSurname() . "! 
            " . $meet->getCreator()->getName() . " " . $meet->getCreator()->getSurname() . ", ".$meet->getCreator()->getPosition().", ".$meet->getCreator()->getCompany().", invites you to the individual online-meeting during the conference Overcoming COVID-19 for Transport Industry.
            Meeting time: ".$meet->getSlot()->getTime()."

        " .

            "<br><a href='http://".$_ENV['DOMAIN']."/api/invite/".$meet->getId()."/confirm'>Approve</a><br>" .
            "<a href='http://".$_ENV['DOMAIN']."/api/invite/".$meet->getId()."/fail'>Reject</a>";
    }

    private function makeTextResponse(Meet $meet){
        return
        $meet->getGuest()->getName() . " " . $meet->getGuest()->getSurname() . " " . ($meet->getStatus() === Meet::CONFIRM ? "принял " : "отклонил") . " встречу для Вас".
        "<br>Время: ".$meet->getSlot()->getTime()."
         
        <br>The online-meeting ".($meet->getStatus() === Meet::CONFIRM ? "aproved " : "rejected")." by ".$meet->getGuest()->getName() . " " . $meet->getGuest()->getSurname().".
        <br>Time: ".$meet->getSlot()->getTime()."
        ";
    }

    private function makeTextResponseControl(Meet $meet){
        return "Приглашение о встрече 30 марта в " . $meet->getSlot()->getTime() . ".
        было " . ($meet->getStatus() === Meet::CONFIRM ? "принято. " : "отклонено") . "Собеседник " . $meet->getGuest()->getName() . " " . $meet->getGuest()->getSurname(). "  Создатель " . $meet->getCreator()->getName() . " " . $meet->getCreator()->getSurname();
    }

//    private function renderHtmlMessage(Message $mes) {
//        try {
//            return $this->renderer->render(
//                'security/message/message.html.twig',
//                ['message' => $mes->getText(), "header" => "На устройстве " . $mes->getEvent()->getDevice()->getName() . " произошло событие \"" . $mes->getEvent()->getTemplate()->getName() . "\"."]
//            );
//        } catch(Exception $e) {
//            return "Не удалось отрендерить html сообщение: " . $mes->getText() . " На устройстве " . $mes->getEvent()->getDevice()->getName() . " произошло событие \"" . $mes->getEvent()->getTemplate()->getName() . "\".";
//        }
//    }
}