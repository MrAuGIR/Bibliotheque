

const rating = () => {

  const ratingComponents = document.querySelectorAll('.rating-stars');

  ratingComponents.forEach(component => {
    const stars = component.querySelectorAll('.star');
    const bookId = component.dataset.bookId;
    const message = component.querySelector('.rating-message');

    stars.forEach(star => {
      star.addEventListener('mouseover', function() {
        const rating = parseInt(component.dataset.currentRating);
        highlightStar(stars, rating);
      })

      star.addEventListener('mouseout', function() {
        const currentRating = parseInt(component.dataset.currentRating);
        highlightStar(stars, currentRating);
      })

      star.addEventListener('click', function() {
        const rating = parseInt(this.dataset.value);
        submitRating(bookId, rating, component, stars, message);
      })
    })
  })
}

const highlightStar = (stars, rating) => {
  stars.forEach(function(s, index) {
    if (index < rating) {
      s.classList.add('active');
    } else {
      s.classList.remove('active');
    }
  });
}

const submitRating = async (bookId, rating, component, stars, message) => {
  const res = await fetch('/book/rating', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      bookId: bookId,
      rating: rating
    })
  });

  const data = await res.json();

  if (res.ok) {
    component.dataset.currentRating = rating;
    highlightStar(stars, rating);
    message.innerText = 'Note Enregistré';
    setTimeout(() => {
      message.innerText = '';
    }, 3000);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  rating();
})
