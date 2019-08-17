<?php defined('_VR360_EXEC') or die; ?>
<?php $user = Vr360Factory::getUser(); ?>
<div class="col-md-12">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand user-avatar" href="#">
					<img
							src="<?php echo Vr360HelperUser::getGravatar($user->email); ?>"
							class="img-circle"
							style="display: inline-block;"
							id="avatar"
					/>
					<span class=""><?php echo $user->name; ?></span>
				</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<div class="navbar-form navbar-left">
					<ul class="nav navbar-nav">
						<li class="">
							<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
							<button type="button" class="btn btn-primary tour-add" id="tour-add">
								<i class="fas fa-plus"></i>
								<?php echo \Joomla\Language\Text::_('TOURS_LABEL_ADD_NEW'); ?>
							</button>
						</li>
					</ul>
				</div>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<!-- Search form -->
						<form
								method="post"
								class="navbar-form navbar-left form-inline pull-right"
								name="search-form"
						>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-btn">
										<button
												type="button"
												class="btn btn-danger"
												id="search-reset"
												value="Reset"
										>
											<i class="fas fa-sync"></i>
										</button>
									</span>
									<input
											type="text"
											class="form-control"
											id="search-keyword"
											data-action="filter"
											data-filters="#task-table"
											placeholder="<?php echo \Joomla\Language\Text::_('TOURS_DESCRIPTION_KEYWORD'); ?>"
											name="keyword"
											value="<?php echo Vr360Factory::getInput()->get('keyword'); ?>"
									/>
								</div>
							</div>
							<button
									class="btn btn-default"
									type="submit"
									id="search-submit"
							>
								<i class="fa fa-search" aria-hidden="true"></i>
							</button>
							<fieldset>
								<input type="hidden" name="view" value="tours"/>
								<input type="hidden" name="task" value="display"/>
							</fieldset>
						</form>
					</li>
					<li>
						<!-- Logout form -->
						<form action="index.php" class="navbar-form navbar-right">
							<input type="hidden" name="view" value="user"/>
							<input type="hidden" name="task" value="logout"/>
							<button
									type="submit"
									id="user-logout"
									class="btn btn-danger">
								<i class="fas fa-sign-out-alt"></i>
								<?php echo \Joomla\Language\Text::_('USER_LABEL_USER_LOGOUT'); ?>
							</button>
						</form>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</div>
