

const enableEditComment = (id) => {
  const element = document.getElementById(id);

  element.addEventListener('click', () => {
    if (element.disable === "false") {
      element.disable = "true";
    } else {
      element.disable = "false";
    }
  });

}

const updateComment = () => {

  document.querySelectorAll("[action-edit]").forEach(btn => {

    btn.addEventListener('click', function() {
      const commentId = this.getAttribute('data-id');
      document.getElementById('form-edit-' + commentId).style.display = 'block';

      document.getElementById('comment' + commentId).style.display = 'none';
      document.getElementById('comment' + commentId + '-action').style.display = 'none';
    })

  })

  document.querySelectorAll("[action-update]").forEach(btn => {

    btn.addEventListener('click', function() {
      const commentId = this.getAttribute('data-id');

      const contenu = document.getElementById('form-edit-' + commentId).querySelector('.edit-comment').value;
      const comment = {
        id: commentId,
        content: contenu
      }

      fetchComment(comment);

      //display non form 
      document.getElementById('form-edit-' + commentId).style.display = 'none';
      document.getElementById('comment' + commentId).style.display = 'block';
      document.getElementById('comment' + commentId + '-action').style.display = 'block';
      document.getElementById('comment' + commentId).querySelector('.card-text').innerText = comment.content;
    })
  })

  document.querySelectorAll("[action-delete]").forEach(btn => {

    btn.addEventListener('click', function() {
      const commentId = this.getAttribute('data-id');

      deleteComment(commentId);
      document.getElementById('comment' + commentId).remove();
      document.getElementById('comment' + commentId + '-action').remove();
    })
  })

}

const fetchComment = async (comment) => {

  const res = await fetch('/comment/' + comment.id, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(comment)
  })

  return await res.json();

}

const deleteComment = async (id) => {

  const res = await fetch('/comment/' + id, {
    method: 'DELETE'
  });
  return res;
}


document.addEventListener('DOMContentLoaded', function() {

  updateComment();
})
