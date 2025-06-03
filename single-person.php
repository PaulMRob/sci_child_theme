<?php get_header(); ?>

<main class="person-profile">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="profile-header">
      <?php if (has_post_thumbnail()) : ?>
        <div class="profile-photo"><?php the_post_thumbnail('medium'); ?></div>
      <?php endif; ?>
      <h1><?php the_title(); ?></h1>
    </div>

    <div class="profile-meta">
      <img class="profile-pic" src="<?php the_field('profile_picture'); ?>" alt="Profile of <?php the_title(); ?>" />
      <h2> <?php the_field('full_name'); ?> - <?php the_field('job_title') ?></h2>
      <?php the_field('positions'); ?>
      <p> <?php the_field('office_desk'); ?> </p>
      
      <?php
        $field = get_field('phone');
          if( $field ): ?> 
            <p> <strong>Phone: </strong><?php the_field('phone'); ?> </p>
      <?php endif; ?>
      
      <?php
        $field = get_field('fax');
          if( $field ): ?> 
            <p> <strong>Fax: </strong><?php the_field('fax'); ?> </p>
      <?php endif; ?>

      <p> <a href="mailto:<?php the_field('email'); ?>"><?php the_field('email'); ?></a></p>
      
      <?php 
        $field = get_field('personal_page');
          if( $field ): ?>
           <p> <a href="<?php the_field('personal_page'); ?>">Personal Page</a></p>
      <?php endif; ?>
      
      <?php 
        $field = get_field('publications');
          if( $field ): ?>
            <p><a href="<?php the_field('publications'); ?>"><strong>Publications</strong></a></p>
      <?php endif; ?>
    </div>

    <div class="profile-bio">
      <?php 
      $field = get_field('background');
      if( $field ): ?>
        <h2>Background</h2>
        <p><?php the_field('background'); ?></p>
      <?php endif; ?>

      <?php 
      $field = get_field('current_responsibilities');
      if( $field ): ?>
        <h2>Current Responsibilities</h2>
        <p><?php the_field('current_responsibilities'); ?></p>
      <?php endif; ?>

      <?php
      $field = get_field('research_interests');
      if( $field ): ?>
        <h2>Research Interests</h2>
        <p><?php the_field('research_interests'); ?></p>
      <?php endif; ?>
    </div>

  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
