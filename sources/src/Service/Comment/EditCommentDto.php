<?php

namespace App\Service\Comment;

class EditCommentDto
{
  public function __construct(
    public int|string $id,
    public string $content
  ) {}
}
