<?php

namespace Drupal\custom_vud\Controller;

use Drupal\votingapi\Entity\Vote;
use Drupal\votingapi\Entity\VoteType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\vud\Controller\VotingApiController;

/**
 * Implements VotingAPI. Provides logical methods to the route endpoints.
 *
 * Class VotingApiController
 *
 * @package Drupal\vud\Controller
 */
class CustomVudApiController extends VotingApiController {

  const CUSTOM_VUD_DEFAULT_TYPE = 'points';
  const CUSTOM_VUD_DEFAULT_TAG = 'vote';

  /**
   * @param $entity_id
   *  EntityId of the referenced entity
   * @param $entity_type_id
   *  EntityTypeId of the referenced entity
   * @param $vote_value
   *  Value of vote to be stored.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function vote($entity_id, $entity_type_id, $vote_value, Request $request) {

//     $entity = $this->entityTypeManager()
//       ->getStorage($entity_type_id)
//       ->load($entity_id);

 //   $vote_storage = $this->entityTypeManager()->getStorage('vote');

//    $voteTypeId = \Drupal::config('vud.settings')->get('tag', 'vote');
//    $voteType = VoteType::load($voteTypeId);

//     $vote_storage->deleteUserVotes(
//       $this->currentUser()->id(),
//       $voteTypeId,
//       $entity_type_id,
//       $entity_id
//     );

//     $this->entityTypeManager()
//       ->getViewBuilder($entity_type_id)
//       ->resetCache([$entity]);

    $vote = Vote::create(['type' => CUSTOM_VUD_DEFAULT_TAG]);
    $vote->setVotedEntityId($entity_id);
    $vote->setVotedEntityType($entity_type_id);
    $vote->setValueType(CUSTOM_VUD_DEFAULT_TYPE);
    $vote->setValue($vote_value);
    $vote->save();

//     $this->entityTypeManager()
//       ->getViewBuilder($entity_type_id)
//       ->resetCache([$entity]);

//     $criteria = [
//       'entity_type' => $entity_type_id,
//       'entity_id' => $entity_id,
//       'value_type' => $voteTypeId,
//     ];

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
      // AJAX request
      return new JsonResponse([
        'vote' => $vote_value,
        'message_type' => 'status',
        'operation' => 'voted',
        'message' => t('Your vote was added.'),
      ]);
    }

    return new RedirectResponse($request->getRequestUri());
    //return new RedirectResponse($entity->toUrl()->toString());
  }

  /**
   * @param $entity_id
   *  EntityId of the referenced entity
   * @param $entity_type_id
   *  EntityTypeId of the referenced entity
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function resetVote($entity_type_id, $entity_id, Request $request){
    $entity = $this->entityTypeManager()
      ->getStorage($entity_type_id)
      ->load($entity_id);

    $voteTypeId = \Drupal::config('vud.settings')->get('tag', 'vote');

    $vote_storage = $this->entityTypeManager()->getStorage('vote');

    $vote_storage->deleteUserVotes(
      $this->currentUser()->id(),
      $voteTypeId,
      $entity_type_id,
      $entity_id
    );

    $this->entityTypeManager()
      ->getViewBuilder($entity_type_id)
      ->resetCache([$entity]);

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
      // AJAX request
      return new JsonResponse([
        'message_type' => 'status',
        'operation' => 'reset',
        'message' => t('Your vote was reset.'),
      ]);
    }

    return new RedirectResponse($entity->toUrl()->toString());
  }


}
