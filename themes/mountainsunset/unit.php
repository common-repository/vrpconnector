<?php
/**
 * Unit Page Template
 *
 * @package VRPConnector
 * @since 1.3.0
 */

global $vrp; ?>
<div id="vrp">
	<div class="vrp-container-fluid">
		<div class="vrp-row" id="unit-data"
		     data-unit-id="<?php echo esc_attr( $data->id ); ?>"
		     data-unit-slug="<?php echo esc_attr( $data->page_slug ); ?>"
		     data-unit-address1="<?php echo esc_attr( $data->Address1 ); ?>"
		     data-unit-address2="<?php echo esc_attr( $data->Address2 ); ?>"
		     data-unit-city="<?php echo esc_attr( $data->City ); ?>"
		     data-unit-state="<?php echo esc_attr( $data->State ); ?>"
		     data-unit-zip="<?php echo esc_attr( $data->PostalCode ); ?>"
		     data-unit-latitude="<?php echo esc_attr( $data->lat ); ?>"
		     data-unit-longitude="<?php echo esc_attr( $data->long ); ?>"
		     data-display-pageviews="<?php echo ( isset( $data->pageViews ) ) ? 'true' : 'false'; ?>"
		>
			<div class="vrp-col-md-9">
				<div class="vrp-row">
					<?php echo esc_html( $data->Name ); ?>
				</div>
				<div class="vrp-row">
					<?php echo esc_html( $data->Bedrooms ); ?> Bedroom(s) | <?php echo esc_html( $data->Bathrooms ); ?>
					Bathroom(s) | Sleeps <?php echo esc_html( $data->Sleeps ); ?>
				</div>
			</div>
			<div class="vrp-col-md-3">
				<button class="vrp-favorite-button vrp-btn" data-unit="<?php echo esc_attr( $data->id ) ?>"></button>
			</div>
		</div>
	</div>

	<div class="vrp-row">
		<div id="tabs">
			<ul>
				<li><a href="#overview">Overview</a></li>
				<li><a href="#amenities">Amenities</a></li>
				<?php if ( isset( $data->reviews[0] ) ) { ?>
					<li><a href="#reviews">Reviews</a></li>
				<?php } ?>
				<li><a href="#calendar">Book</a></li>
				<li><a href="#rates">Rates</a></li>
				<?php if ( isset( $data->lat ) && isset( $data->long ) ) { ?>
					<li><a href="#gmap" id="gmaplink">Map</a></li>
				<?php } ?>
			</ul>

			<!-- OVERVIEW TAB -->
			<div id="overview">
				<div class="vrp-row">
					<div class="vrp-col-md-12">
						<!-- Photo Gallery -->
						<div id="photo">
							<?php foreach ( $data->photos as $index => $photo ) : ?>
								<?php $style = ( $index > 0 ) ? 'display:none;' : ''; ?>
								<div id="vrp-photo-full-<?php echo esc_attr( $photo->id ); ?>"
								     class="vrp-photo-container"
								     style="<?php echo esc_attr( $style ); ?>">
									<img alt="<?php echo esc_attr( $photo->caption ); ?>"
									     src="<?php echo esc_url( $photo->url ); ?>"
									     style="width:100%;"/>
									<?php if ( ! empty( $photo->caption ) ) : ?>
										<div id="caption_<?php echo esc_attr( $photo->id ); ?>" class="caption">
											<?php echo esc_html( $photo->caption ); ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>

						<div id="gallery">
							<?php foreach ( $data->photos as $photo ) : ?>
								<?php $photo->thumb_url = ( empty( $photo->thumb_url ) ) ? $photo->url : $photo->thumb_url; ?>
								<img class="thumb"
								     id="<?php echo esc_attr( $photo->id ); ?>"
								     alt="<?php echo esc_attr( $photo->caption ); ?>"
								     src="<?php echo esc_url( $photo->thumb_url ); ?>"/>
							<?php endforeach; ?>
						</div>
						<br style="clear:both;" class="clearfix">
					</div>
				</div>
				<div class="vrp-row">
					<div class="vrp-col-md-12">
						<div id="description">
							<p><?php echo wp_kses_post( nl2br( $data->Description ) ); ?></p>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="amenities">
				<table class="amenTable" cellspacing="0">
					<tr>
						<td colspan="2" class="heading"><h4>Amenities</h4></td>
					</tr>
					<?php foreach ( $data->attributes as $amen ) { ?>

						<tr>
							<td class="first">
								<b><?php echo esc_html( $amen->name ); ?></b>:
							</td>
							<td> <?php echo esc_html( $amen->value ); ?></td>
						</tr>

					<?php } ?>
				</table>
			</div>

			<?php if ( isset( $data->reviews[0] ) ) { ?>
				<div id="reviews">
					<section id="reviews">
						<h2>Guest Reviews of
							<span class="fn">
								<?php echo esc_html( strtolower( $data->Name ) ); ?>
							</span>
						</h2>
						<span class="address serif">
							<?php echo esc_html( $data->Address2 ); ?>
							<?php echo esc_html( $data->City ); ?>
							,&nbsp;<?php echo esc_html( $data->State ); ?>
						</span>

						<?php
						$total = 0;
						$rat   = 0;
						foreach ( $data->reviews as $review ) {
							$rat += $review->rating;
							$total ++;
						}
						$av = round( $rat / $total, 2 );
						?>

						<div class="hreview-aggregate" style="font-size:11px;">
							<hr>
							<div class="item vcard">
								<div style="float:right;text-align:center;" class="serif one-third">
									<span class="rating">
										<span class="average"><?php echo esc_html( $av ); ?></span>
										&nbsp;out&nbsp;of&nbsp;
										<span class="best">5</span>
									</span>&nbsp;stars
									based&nbsp;on
									<span class="count"><?php echo esc_html( $total ); ?></span>
									&nbsp;user&nbsp;reviews
								</div>
							</div>
						</div>

						<?php foreach ( $data->reviews as $review ) : ?>
							<div class="review-post">
								<div class="hreview">
									<h3 class="title" style="margin-bottom:12px;">
										<?php echo esc_html( $review->title ); ?>
									</h3>

									<?php if ( ! empty( $review->name ) ) : ?>
										<b class="reviewer vcard">
											Review by
											<span class="fn">
												<?php echo esc_html( $review->name ); ?>
											</span>
										</b>
									<?php endif; ?>

									<div class="description item vcard">
										<span class="serif">
											<?php echo esc_html( strip_tags( $review->review ) ); ?>
										</span>
										<br>
										<b class="rating"><?php echo esc_html( $review->rating ); ?> out of 5 stars</b>
									</div>
								</div>
								<?php if ( ! empty( $review->response ) ) { ?>
									<div class="reviewresponse"
									     style="margin-top:1em;padding-top:1em;border-top:1px solid #dadada;">
										<h5 class="title"> Manager Response:</h5>
										<?php echo esc_html( $review->response ); ?>
									</div>
								<?php } ?>
							</div>

						<?php endforeach; ?>
				</div>
			<?php } ?>

			<!-- CALENDAR TAB -->
			<div id="calendar">
				<div class="vrp-row">
					<div class="vrp-col-md-12">
						<div id="checkavailbox">
							<h1 class="bookheading">Book Your Stay!</h1><br>

							<div id="datespicked">
								Select your arrival and departure dates below to reserve this unit.<br><br>

								<form action="<?php echo esc_url( site_url( '/vrp/book/step3/', 'https' ) ); ?>"
								      method="get" id="bookingform">

									<table align="center" width="96%">
										<tr>
											<td width="40%">Arrival:</td>
											<td>
												<input type="text" id="check-availability-arrival-date"
												       name="obj[Arrival]"
												       class="input unitsearch"
												       value="<?php echo esc_attr( $vrp->search->arrival ); ?>">
											</td>
										</tr>
										<tr>
											<td>Departure:</td>
											<td>
												<input type="text" id="check-availability-departure-date"
												       name="obj[Departure]"
												       class="input unitsearch"
												       value="<?php echo esc_attr( $vrp->search->depart ); ?>">
											</td>
										</tr>

										<?php if ( 'Escapia' === $data->manager->Name && ! empty( $data->additonal->PetsPolicy ) ) : ?>
											<?php // <!-- Escapia PMS ONLY - Booking w/Pets --> ?>
											<?php if ( 2 === $data->additonal->PetsPolicy ) : ?>
												<?php $pets_type = 'Dog'; ?>
											<?php elseif ( 1 === $data->additonal->PetsPolicy ) : ?>
												<?php $pets_type = 'Cat'; ?>
											<?php endif; ?>

											<tr>
												<td>Pets:</td>
												<td>
													<select name="obj[Pets]">
														<option value="">None</option>
														<option
															value="<?php echo esc_attr( $data->additonal->PetsPolicy ) ?>">
															<?php echo esc_attr( $pets_type ) ?>(s)
														</option>
													</select>
												</td>
											</tr>
										<?php endif; ?>


										<?php if ( 'Barefoot' !== $data->manager->Name ) :
											// Barefoot property management software requires Promo Codes are added during checkout and does not support using them on the initial quote request.
											// All other PMS do support promo codes during the initial quote request: Escapia, ISILink, RNS, etc.
											?>
											<tr>
												<td>Promo Code</td>
												<td>
													<input type="text"
													       name="obj[PromoCode]"
													       value=""
													       placeholder="Promo Code">
												</td>
											</tr>
										<?php endif; ?>

										<tr>
											<td colspan="2" id="errormsg">
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<table id="ratebreakdown"></table>
											</td>
										</tr>

										<tr>
											<td>
												<input type="hidden"
												       name="obj[PropID]"
												       value="<?php echo esc_attr( $data->id ); ?>">
												<input type="button"
												       value="Check Availability"
												       class="bookingbutton vrp-btn"
												       id="checkbutton">
											</td>
											<td>
												<input type="submit"
												       value="Book Now!"
												       id="booklink"
												       class="vrp-btn"
												       style="display:none;"/>
											</td>
										</tr>
									</table>

								</form>
							</div>
						</div>
					</div>

					<div class="vrp-col-md-12">
						<div id="availability" style="">
							<?php echo wp_kses_post( vrp_calendar( $data->avail ) ); ?>
						</div>
					</div>
				</div>
			</div>

			<div id="rates">
				<div class="row">
					<h3> Seasonal Rates </h3>

					<div id="rates">
						<?php

						$rate_seasons = [];
						foreach ( $data->rates as $rate ) {
							$start = date( 'm/d/Y', strtotime( $rate->start_date ) );
							$end   = date( 'm/d/Y', strtotime( $rate->end_date ) );

							if ( empty( $rate_seasons[ $start . ' - ' . $end ] ) ) {
								$rate_seasons[ $start . ' - ' . $end ] = new \stdClass();
							}

							if ( 'Monthly' === $rate->chargebasis ) {
								$rate_seasons[ $start . ' - ' . $end ]->monthly
									= '$' . number_format( $rate->amount, 2 );
							}
							if ( 'Daily' === $rate->chargebasis ) {
								$rate_seasons[ $start . ' - ' . $end ]->daily
									= '$' . number_format( $rate->amount, 2 );
							}
							if ( 'Weekly' === $rate->chargebasis ) {
								$rate_seasons[ $start . ' - ' . $end ]->weekly
									= '$' . number_format( $rate->amount, 2 );
							}
						}
						?>

						<table class="rate">
							<tr>
								<th>Date Range</th>
								<th>Daily</th>
								<th>Weekly</th>
								<th>Monthly</th>
							</tr>
							<?php foreach ( $rate_seasons as $date_range => $rates ) { ?>
								<tr>
									<td><?php echo esc_html( $date_range ); ?></td>
									<td><?php echo ( ! empty( $rates->daily ) ) ? esc_html( $rates->daily ) : 'N/A'; ?></td>
									<td><?php echo ( ! empty( $rates->weekly ) ) ? esc_html( $rates->weekly ) : 'N/A'; ?></td>
									<td><?php echo ( ! empty( $rates->monthly ) ) ? esc_html( $rates->monthly ) : 'N/A'; ?></td>
								</tr>
							<?php } ?>
						</table>
						* Seasonal rates are only estimates and do not reflect taxes or additional fees.
					</div>
				</div>
			</div>

			<div id="gmap">
				<div id="map" style="width:100%;height:500px;"></div>
			</div>

		</div>
	</div>
</div>
