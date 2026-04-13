export const notificationHandlers = {
    '.NewFollowerEvent': (e) => ({
        id: e.id,
        type: 'newFollower',
        message: `${e.follower.name} started following you!`,
    }),

    '.CarLikedEvent': (e) => ({
        id: e.id,
        type: 'carLiked',
        message: `${e.liker.name} liked your car "${e.car.manufacture} ${e.car.model}"`,
    }),

    '.CarCommentEvent': (e) => ({
        id: e.id,
        type: 'carComment',
        message: `${e.commenter.name} wrote something about you car: ${e.car.manufacture} ${e.car.model}`,
    }),

    '.ReplyLikedEvent': (e) => ({
        id: e.id,
        type: "replyLiked",
        message: `${e.liker.name} liked your reply "${e.reply.content}..."`,
    }),

    '.ReplyEvent': (e) => ({
        id: e.id,
        type: "reply",
        message: `${e.commenter.name} wrote something about your comment on the ${e.car.manufacture} ${e.car.model} post`,
    }),

    '.FollowingCarCreatedEvent': (e) => ({
        id: e.id,
        type: "followingCarCreated",
        message: `${e.following.name} has posted their new car: ${e.car.manufacture} ${e.car.model}`
    }),

    '.FollowingCarUpdatedEvent': (e) => ({
        id: e.id,
        type: "followingCarUpdated",
        message: `${e.following.name} has updated their car: ${e.car.manufacture} ${e.car.model}`
    }),
};