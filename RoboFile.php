<?php

use degov\Scripts\Robo\RunsTrait;

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

  use RunsTrait;

  public function degovUpdate(): void {
    $this->init();

    $this->checkRequirements();

    $this->runComposerUpdate();

    $this->runTranslationsUpdate();

    $this->runDrupalUpdateHooks();

    $this->runEntityUpdates();

    $this->runConfigurationExportIntoFilesystem();

    $this->say('Congratulations! Finished the deGov update.');
  }

  public function degovNewIssue(string $gitBranchName): void {
    $this->init();

    $degovFolderLocation = $this->rootFolderPath . '/docroot/profiles/contrib/degov';

    $this->ensureGitRepo($degovFolderLocation, 'git@bitbucket.org:publicplan/degov.git', 'degov');

    $this->newGitBranch($degovFolderLocation, $gitBranchName);
  }

  public function projectNewIssue(string $gitBranchName): void {
    $this->newGitBranch($this->rootFolderPath, $gitBranchName, 'remotes/origin/develop');
  }

}
